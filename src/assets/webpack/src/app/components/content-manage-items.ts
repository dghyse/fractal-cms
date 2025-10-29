import {bindable, customElement, IDisposable, IEventAggregator, ILogger, INode, IPlatform, resolve} from 'aurelia';
import {ApiServices} from "../services/api-services";
import {EEvents} from "../enums/events";
import {IActionEvent} from "../interfaces/events";
import {IAlertAddMessage} from "../interfaces/alert";
import {EAlert} from "../enums/alert";
import {EApi} from "../enums/api";

@customElement('fractalcms-content-manage-items')

export class ContentManageItems
{
    @bindable public id:number;
    @bindable public view : any;
    @bindable public itemApiUrl : string;
    private form:HTMLFormElement;
    private buttonAdd:HTMLButtonElement;
    private eaActionDispose:IDisposable;

    constructor(
        private readonly logger: ILogger = resolve(ILogger),
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly apiServices: ApiServices = resolve(ApiServices),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement
    ) {
        this.logger = logger.scopeTo('ContentManage');
        this.logger.trace('constructor');
    }

    public binding() {
        this.logger.trace('binding');
    }

    public attached() {
        this.logger.trace('attached', this.id);
        this.form = <HTMLFormElement>this.element.closest('form');
        this.prepare();

    }

    public bound() {
        this.logger.trace('bound');
        this.eaActionDispose = this.ea.subscribe(EEvents.ACTION_BUTTON, this.onAction);
    }


    public unbinding()
    {
        this.logger.trace('unbinding');
        this.eaActionDispose.dispose();
        if (this.buttonAdd) {
            this.buttonAdd.removeEventListener('click', this.onAdd);
        }
    }

    private prepare()
    {
        this.buttonAdd = this.element.querySelector('button[name="addItem"]');
        this.logger.trace('attached', this.id);
        if (this.buttonAdd) {
            this.buttonAdd.addEventListener('click', this.onAdd);
        }
    }

    private readonly onAdd = (event:Event) => {
        this.logger.trace('onAdd');
        const target:HTMLElement = <HTMLElement>event.target;
        if (target) {
            const button:HTMLButtonElement = target.closest('button');
            if (button) {
                event.preventDefault();
                this.sendAction(button.name, button.value);
            }
        }
    }

    private readonly onAction = (message:IActionEvent) => {
        this.logger.trace('onAction');
        this.sendAction(message.action, message.value);
    }

    private  sendAction(name:any, value:any)
    {
        this.logger.trace('sendAction');
        if (this.form) {
            const formData = new FormData(this.form);
            formData.append(name, value);
            const itemApiUrl = this.itemApiUrl.replace('{targetId}', this.id.toString())
            this.apiServices.manageItems(itemApiUrl, formData).then((html) => {
                if (name !== EAlert.ADD_ITEM) {
                    const message:IAlertAddMessage = {
                        id:window.crypto.randomUUID(),
                        message:'Un item a été ajouté',
                        color:'alert-success'
                    };
                    this.ea.publish(EEvents.ACTION_ADD_ALERT, message);
                }
                this.view = html;
                this.prepare();
            }).catch((error) => {
                this.logger.error(error.text());
                const message:IAlertAddMessage = {
                    id:window.crypto.randomUUID(),
                    message:'Un erreur c\'est produite',
                    color:'alert-danger'
                };
                this.ea.publish(EEvents.ACTION_ADD_ALERT, message);
            });
        }
    }
}

