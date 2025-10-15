import {bindable, customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";
import Sortable from 'sortablejs';
@customAttribute('cms-menu-item-list')
export class MenuItemList {

    private lines:NodeList;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('MenuItemList'),
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
        this.init();
    }
    public detached()
    {
        this.logger.trace('detached');
    }

    private init()
    {
        this.logger.trace('addEvent');
    }


}