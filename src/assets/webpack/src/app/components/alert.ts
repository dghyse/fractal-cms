import {bindable, customElement, IEventAggregator, ILogger, INode, IPlatform, resolve} from 'aurelia';
import {EEvents} from "../enums/events";

@customElement('cms-alert')

export class Alert
{
    @bindable public id:string;
    @bindable public message : string;
    @bindable public color : string;

    constructor(
        private readonly logger: ILogger = resolve(ILogger),
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly platform: IPlatform = resolve(IPlatform),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement
    ) {
        this.logger = logger.scopeTo('Alert');
        this.logger.trace('constructor');
    }

    public binding() {
        this.logger.trace('binding');
    }

    public attached() {
        this.logger.trace('attached', this.id);
        this.platform.setTimeout(() => {
            this.close();
        }, 1500);
    }

    public onClose(event:Event){
        this.logger.trace('onClose');
        event.preventDefault();
        this.close();
    }

    private close()
    {
        if (this.element) {
            const alert = this.element.querySelector('div.alert');
            if(alert) {
                alert.remove();
                this.ea.publish(EEvents.ACTION_ALERT_DELETE, {id:this.id});
            }
        }
    }
}

