import {bindable, customElement, IDisposable, IEventAggregator, ILogger, INode, IPlatform, resolve} from 'aurelia';
import {ApiServices} from "../services/api-services";
import {EEvents} from "../enums/events";
import {IAlertAddMessage, IAlertDeleteMessage} from "../interfaces/alert";

@customElement('cms-manage-alerts')

export class ManageAlerts
{
    @bindable() public contactId:number;
    public alerts:IAlertAddMessage[] = [];
    private eaAddDispose:IDisposable;
    private eaDeleteDispose:IDisposable;

    constructor(
        private readonly logger: ILogger = resolve(ILogger),
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly apiServices: ApiServices = resolve(ApiServices),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement
    ) {
        this.logger = logger.scopeTo('ManageAlerts');
        this.logger.trace('constructor');
    }

    public binding() {
        this.logger.trace('binding');
    }

    public attached() {
        this.logger.trace('attached');

    }

    public bound() {
        this.logger.trace('bound');
        this.eaAddDispose = this.ea.subscribe(EEvents.ACTION_ELEMENT_UPDATE, this.onAdd);
        this.eaDeleteDispose = this.ea.subscribe(EEvents.ACTION_ALERT_DELETE, this.onDelete);
    }


    public unbinding()
    {
        this.logger.trace('unbinding');
        this.eaAddDispose.dispose();
        this.eaDeleteDispose.dispose();
    }

    private readonly onAdd = (message:IAlertAddMessage) => {
        this.logger.trace('onAdd');
        this.alerts.push({
            id:message.id,
            message:message.message,
            color:message.color
        });
    }

    private readonly onDelete = (message:IAlertDeleteMessage) => {
        this.logger.trace('onDelete');
        let index:number|null = null;
        this.alerts.forEach((alert, alertIndex) => {
            if (alert.id == message.id) {
                index = alertIndex;
            }
        });
        if (index !== null) {
            this.alerts.splice(index, 1);
        }
    }
}

