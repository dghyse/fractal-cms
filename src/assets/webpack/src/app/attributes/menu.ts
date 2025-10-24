import {customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";

@customAttribute('cms-menu')
export class Menu {

    private actionButtons:NodeList;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('Menu'),
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
        this.actionButtons = this.element.querySelectorAll('[data-toggle="dropdown"]');
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
            const idControls:string = target.getAttribute('aria-controls');
            if (idControls) {
                this.closeOthers(idControls);
                const nav:HTMLElement = this.element.querySelector('#'+idControls);
                if (nav) {
                    if (nav.classList.contains('show')) {
                        nav.classList.remove('show');
                        nav.setAttribute('aria-expanded', 'false');
                        target.setAttribute('aria-expanded', 'false');
                    } else {
                        nav.classList.add('show');
                        nav.setAttribute('aria-expanded', 'true');
                        target.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        }
    }

    private closeOthers(id:string)
    {
        this.logger.trace('closeOthers');
        const dropdownMenu:NodeListOf<HTMLElement> = this.element.querySelectorAll('.dropdown-menu');
        dropdownMenu.forEach((element:HTMLElement, key) => {
            if (element.id != id) {
                element.classList.remove('show');
            }
        });
    }
}