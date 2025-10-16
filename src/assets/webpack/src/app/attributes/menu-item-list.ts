import {bindable, customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";
import {IMenuItem} from "../interfaces/menu-item";
@customAttribute('cms-menu-item-list')
export class MenuItemList {

    private items:NodeListOf<HTMLLIElement>;
    private dragItem:HTMLLIElement;
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
        this.items = this.element.querySelectorAll('li');
        this.init();
    }
    public detached()
    {
        this.logger.trace('detached');
        if (this.items) {
            this.items.forEach((item:HTMLLIElement, index)=> {
                item.removeEventListener('dragstart', this.ondragstart);
                item.removeEventListener('dragend', this.dragend);
                item.removeEventListener('dragover', this.dragover);
                item.removeEventListener('dragleave', this.dragleave);
                item.removeEventListener('drop', this.drop);
            });
        }
    }

    private init()
    {
        this.logger.trace('init');

        this.items.forEach((item:HTMLLIElement, index)=> {
            item.addEventListener('dragstart', this.ondragstart);
            item.addEventListener('dragend', this.dragend);
            item.addEventListener('dragover', this.dragover);
            item.addEventListener('dragleave', this.dragleave);
            item.addEventListener('drop', this.drop);
        });
    }

    private ondragstart = (event:Event) => {
        this.logger.trace('ondragstart');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        item.classList.add('dragging');
        this.dragItem = item;
    }

    private dragend = (event:Event) => {
        this.logger.trace('dragend');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        if (this.dragItem == item) {
            item.classList.remove('over');
            item.classList.remove('dragging');
        }
    }

    private dragover = (event:Event) => {
        this.logger.trace('dragover');
        event.preventDefault();
        const item:HTMLLIElement = event.target as HTMLLIElement;
        const target:HTMLLIElement = item.closest('li');
        if (target) {
            target.classList.add('over');
        }
    }
    private dragleave = (event:Event) => {
        this.logger.trace('dragleave');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        item.classList.remove('over');
    }

    private drop = (event:Event) => {
        this.logger.trace('drop', event);
        event.stopPropagation();
        const item:HTMLElement = event.target as HTMLElement;
        const target:HTMLLIElement = item.closest('li');
        if (target && this.dragItem && this.dragItem !== target) {
            const menuItemData = this.buildMenuItemData(target, this.dragItem);
            this.apiService.manageMenuItems(
                parseInt(target.getAttribute('data-menu-id')),
                menuItemData).then((html) => {
                this.dragItem.classList.remove('over');
                this.dragItem.classList.remove('dragging');
                target.classList.remove('over');
                target.classList.remove('dragging');
                target.parentNode.insertBefore(this.dragItem, target);
                this.detached();
                this.element.innerHTML = html;
                this.attached();
                this.logger.trace('drop Item déplacé !!!', menuItemData);
            }).catch((error) => {
                this.logger.warn(error);
                this.logger.trace('drop Item ERROR !!!', error);
            });

        }
    }

    private buildMenuItemData(target:HTMLLIElement, dragItem:HTMLLIElement)
    {
        this.logger.trace('buildMenuItemData');
        const menuItem:IMenuItem = {
            sourceMenuItemId:parseInt(dragItem.getAttribute('data-id')),
            sourceIndex:parseInt(dragItem.getAttribute('data-index')),
            destMenuItemId:parseInt(target.getAttribute('data-id')),
            destIndex:parseInt(target.getAttribute('data-index'))
        };
        return menuItem;
    }
}