import {customAttribute, ILogger, resolve, IPlatform, IDisposable, IEventAggregator, INode} from "aurelia";
import {IAlertMessage} from "../interfaces/alert";
import {EEvents} from "../enums/events";

@customAttribute('cms-alert')
export class Alert {

    public text:string;
    public color:string;
    public enabled:boolean = false;
    private subscriptionFileEnd: IDisposable;
    private mainContainer:HTMLElement;

    public constructor(
        private readonly logger: ILogger = resolve(ILogger),
        private readonly platform: IPlatform = resolve(IPlatform),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly ea: IEventAggregator = resolve(IEventAggregator)
    ) {
        this.logger = logger.scopeTo('Alert');
        this.logger.trace('constructor');
    }

    public attached() {
        this.logger.trace('attached');
        this.subscriptionFileEnd = this.ea.subscribe(EEvents.ACTION_ELEMENT_UPDATE, this.addAlert);
        this.mainContainer = this.platform.document.querySelector('#main');
    }

    public dispose() {
        this.logger.trace('dispose');
        this.subscriptionFileEnd.dispose();
    }
    public detached() {
        this.logger.trace('detaching');
    }

    private readonly addAlert = (message:IAlertMessage) => {
        this.logger.trace('addAlert');
        this.pushAlert(message);
    }

    public onClose = (event:Event) =>{
        this.logger.trace('onClose');
        event.preventDefault();
        const target:HTMLElement = event.currentTarget as HTMLElement;
        if (target) {
            const alert = target.closest('div.alert');
            if(alert) {
                alert.remove();
            }
        }
    }

    public pushAlert(alert:IAlertMessage)
    {
        this.logger.trace('pushAlert');
        /**
         *     <div class="alert alert-primary p-2 sticky-top" class.bind="color" show.bind="enabled" role="alert">
         *             <div class="col-sm-11">
         *                 ${text}
         *             </div>
         *             <div class="col-sm-1 m-0 text-center">
         *                 <button type="button" class="btn btn-close" aria-label="Fermer cette alert" click.trigger="onClose($event)">
         *                 </button>
         *             </div>
         *     </div>
         */
        const alertHtml:HTMLElement = this.platform.document.createElement('div');
        alertHtml.classList.add('alert', 'alert-primary', 'p-2', 'row', 'align-items-center', 'sticky-top');
        alertHtml.classList.add(alert.color);
        alertHtml.role ='alert';
        alertHtml.ariaLabel ='notification de modification';
        alertHtml.id = alert.id;
        const alertText:HTMLElement = this.platform.document.createElement('div');
        alertText.classList.add('col-sm-11');
        alertText.append(alert.text);
        const alertButton:HTMLElement = this.platform.document.createElement('div');
        alertButton.classList.add('col-sm-1', 'm-0', 'text-center');
        const button:HTMLButtonElement = this.platform.document.createElement('button');
        button.classList.add('btn', 'btn-close');
        button.type = 'button';
        button.addEventListener('click', this.onClose);
        alertButton.append(button);
        alertHtml.append(alertText);
        alertHtml.append(alertButton);
        if (this.mainContainer) {
            this.mainContainer.prepend(alertHtml);
        }
        this.platform.taskQueue.queueTask(() => {
            this.platform.setTimeout(() => {
                const domAlert = this.platform.document.getElementById(alert.id);
                if (domAlert) {
                  domAlert.remove();
                }
            }, 2250);
        }, {delay:250});

    }
}