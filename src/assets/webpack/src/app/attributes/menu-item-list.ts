import {customAttribute, IEventAggregator, ILogger, INode, IPlatform, resolve} from "aurelia";
import {ApiServices} from "../services/api-services";
import {IMenuItem, IMoveMenuItem} from "../interfaces/menu-item";
import {EEvents} from "../enums/events";
import {EMenuItemAction} from "../enums/menu";

@customAttribute('cms-menu-item-list')
export class MenuItemList {

    private items:NodeListOf<HTMLLIElement>;
    private deleteLinks:NodeListOf<HTMLLinkElement>;
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
        this.deleteLinks = this.element.querySelectorAll('.user-button-delete');
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
        if (this.deleteLinks) {
            this.deleteLinks.forEach((link:HTMLLinkElement, index) => {
               link.removeEventListener('click', this.onDelete);
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

        if (this.deleteLinks) {
            this.deleteLinks.forEach((link:HTMLLinkElement, index) => {
                link.addEventListener('click', this.onDelete);
            });
        }
    }

    public onDelete = (event:Event) => {
        this.logger.trace('onDelete');
        event.preventDefault();
        const target = event.currentTarget as HTMLLinkElement;
        if (target) {
            if (confirm('Attention !! vous allez supprimer définitivement cet élément ? ') === true) {
                const href:string = target.href;
                this.apiService.delete(href).then((response:any) => {
                    this.platform.taskQueue.queueTask(() => {
                        this.platform.window.location.reload();
                    });
                }).catch((error:any) => {
                    this.logger.trace('Delete  ERROR', error);
                });
            }
        }
    }

    private readonly ondragstart = (event:Event) => {
        this.logger.trace('ondragstart');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        item.classList.add('dragging');
        this.dragItem = item;
    }

    private readonly dragend = (event:Event) => {
        this.logger.trace('dragend');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        if (this.dragItem == item) {
            item.classList.remove('over');
            item.classList.remove('dragging');
        }
    }

    private readonly dragover = (event:Event) => {
        this.logger.trace('dragover');
        event.preventDefault();
        const item:HTMLLIElement = event.target as HTMLLIElement;
        const target:HTMLLIElement = item.closest('li');
        if (target) {
            target.classList.add('over');
        }
    }
    private readonly dragleave = (event:Event) => {
        this.logger.trace('dragleave');
        const item:HTMLLIElement = event.target as HTMLLIElement;
        item.classList.remove('over');
    }

    private readonly drop = (event:Event) => {
        this.logger.trace('drop', event);
        event.stopPropagation();
        const item:HTMLElement = event.target as HTMLElement;
        const target:HTMLLIElement = item.closest('li');
        if (target && this.dragItem && this.dragItem !== target) {
            const menuItemData = this.buildMenuItemData(target, this.dragItem);
            this.apiService.manageMenuItems(
                Number.parseInt(target.getAttribute('data-menu-id')),
                menuItemData).then((html) => {
                    const messageMove:IMoveMenuItem = {
                        name:'menu-item-list',
                        action:EMenuItemAction.detached
                    };
                    this.ea.publish(EEvents.ACTION_MOVE_MENU_ITEM_BEFORE, messageMove);
                    this.dragItem.classList.remove('over');
                    this.dragItem.classList.remove('dragging');
                    target.classList.remove('over');
                    target.classList.remove('dragging');
                    target.parentNode.insertBefore(this.dragItem, target);
                    this.platform.taskQueue.queueTask(() => {
                        this.detached();
                        this.element.innerHTML = html;
                        this.attached();

                        this.platform.requestAnimationFrame(() => {
                            const messageMove:IMoveMenuItem = {
                                name:'menu-item-list',
                                action:EMenuItemAction.attached
                            };
                            this.ea.publish(EEvents.ACTION_MOVE_MENU_ITEM_AFTER, messageMove);
                            this.logger.trace('drop Item déplacé !!!', menuItemData);
                        });
                    }, {delay:150});

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
            sourceMenuItemId:Number.parseInt(dragItem.getAttribute('data-id')),
            destMenuItemId:Number.parseInt(target.getAttribute('data-id')),
        };
        return menuItem;
    }
}