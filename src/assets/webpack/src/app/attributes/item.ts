import {bindable, customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";
import {IActionEvent} from "../interfaces/events";
import {EEvents} from "../enums/events";

@customAttribute('cms-item')
export class Item {

    @bindable() id: number;

    private actionButtons:NodeList;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('Item'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly apiService: ApiServices = resolve(ApiServices),
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
    }

    public attached()
    {
        this.logger.trace('attached');
        this.addEvent();
    }
    public detached()
    {
        this.logger.trace('attached');
        this.removeEvent();
    }

    private addEvent()
    {
        this.logger.trace('addEvent');
        this.actionButtons = this.element.querySelectorAll('.actionButtons');
       this.actionButtons.forEach((ele, index) => {
           ele.addEventListener('click', this.onAction);
       });
    }

    private removeEvent()
    {
        this.logger.trace('removeEvent');
        this.actionButtons.forEach((ele, index) => {
            ele.removeEventListener('click', this.onAction);
        });

    }
    private readonly onAction = (event:Event) => {
        this.logger.trace('onAction');
        let target:HTMLElement = <HTMLElement>event.target;
        if (target) {
            if (target.nodeName !== 'button') {
                target = target.closest('button');
            }
            if (target) {
                event.preventDefault();
                const elementName = target.getAttribute('name');
                const elemenValue = target.getAttribute('value');
                const message:IActionEvent = {
                    action:elementName,
                    value:elemenValue
                };
                this.platform.taskQueue.queueTask(() => {
                    this.ea.publish(EEvents.ACTION_BUTTON, message);
                });
            }
        }
    }
}