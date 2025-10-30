import {bindable, customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";
import {text} from "node:stream/consumers";

export interface IItemChoice {
    value:any;
    content:string
}

@customAttribute('fractalcms-select-beautiful')
export class SelectBeautiful {

    @bindable() multiple: boolean = true;
    @bindable() inputName : string;
    @bindable() searchInputName : string = 'model[search]';
    private listElement:HTMLUListElement;
    private readonly options:HTMLOptionElement[];
    private optionsFiltered:HTMLOptionElement[];
    private divContainer:HTMLDivElement;
    private divSearchContainer:HTMLDivElement;
    private divItemContainer:HTMLDivElement;
    private divLiveMsg:HTMLDivElement;
    private inputSearch:HTMLInputElement;
    private currentChoiced:IItemChoice[] = [];
    private listOpen:boolean = false;
    private activeItemNewIndex:number = -1;
    private activeItemPrevIndex:number;
    private availableKeyboard = [
        'ArrowDown',
        'ArrowUp',
        'Enter',
        'Escape',
    ];

    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('SelectBeautiful'),
        private readonly element: HTMLSelectElement = resolve(INode) as HTMLSelectElement,
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly apiService: ApiServices = resolve(ApiServices),
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
        this.options = [] as HTMLOptionElement[];
    }

    public attached()
    {
        this.logger.trace('attached');
        if (this.multiple) {
            this.init();
        }
    }
    public detached()
    {
        this.logger.trace('attached');
        if(this.inputSearch) {
            this?.inputSearch.removeEventListener('input', this.onSearch);
        }
    }

    private init()
    {
        this.logger.trace('init');
        if (this.multiple) {
            this.element.setAttribute('multiple', 'true');
        }
        const listLabel = this.element.getAttribute('prompt');
        this.element.style.display = 'none';
        //Store select options
        this.element.querySelectorAll('option').forEach((option, key) => {
            if (option.value) {
                this.options.push(option);
                if (option.selected) {
                    option.setAttribute('aria-selected', 'true');
                    const itemChoice:IItemChoice = {
                        value:option.value,
                        content:option.textContent
                    };
                    this.currentChoiced.push(itemChoice);
                }
            }

        });
        //Create ul list item
        this.listElement = this.platform.document.createElement('ul');
        this.closeList()
        this.listElement.setAttribute('role', 'listbox');
        this.listElement.setAttribute('aria-label', listLabel);
        this.listElement.setAttribute('aria-multiselectable', 'true');
        this.listElement.setAttribute('tabindex', '0');
        this.listElement.classList.add('list-items-container')
        this.buildList(this.options);

        //Create div container
        this.divContainer = this.platform.document.createElement('div');
        this.divContainer.classList.add('select-beautiful-container');
        //Create input search
        this.inputSearch = this.platform.document.createElement('input');
        this.inputSearch.type = 'text';
        this.inputSearch.classList.add('input-search')
        this.inputSearch.name = this.searchInputName;
        this.inputSearch.setAttribute('autocomplete', 'off');
        this.inputSearch.placeholder = 'Rechercher';
        this.inputSearch.setAttribute('aria-label', 'Rechercher');
        this.inputSearch.addEventListener('input', this.onSearch);
        this.inputSearch.addEventListener('focusin', this.onFocus);
        this.inputSearch.addEventListener('focusout', this.onFocusout);
        this.inputSearch.addEventListener('keydown', this.onKeydown);
        //Create item container
        this.divItemContainer = this.platform.document.createElement('div');
        this.divItemContainer.classList.add('item-container');
        this.divItemContainer.append(this.inputSearch);
        this.divSearchContainer = this.platform.document.createElement('div');
        this.divSearchContainer.classList.add('search-container');
        this.divSearchContainer.append(this.listElement);

        //Create div live message
        this.divLiveMsg = this.platform.document.createElement('div');
        this.divLiveMsg.setAttribute('aria-live','polite');
        this.divLiveMsg.classList.add('sr-only');
        //Append element in Dom
        this.element.before(this.divItemContainer);
        this.element.before(this.divSearchContainer);
        this.element.before(this.divLiveMsg);

        //Add selected item
        this.listElement.querySelectorAll('li').forEach((li:HTMLLIElement, key:number) => {
            const selected = li.getAttribute('aria-selected');
            if (selected == 'true') {
                this.addItem(li);
            }
        });
    }


    /**
     * Search in option
     *
     * @param event
     */
    private readonly onSearch = (event:Event) => {
        this.logger.trace('onSearch');
        event.preventDefault();
        this.openList()
        const target = event.currentTarget as HTMLInputElement;
        const value = target.value.trim().toLowerCase();
        this.optionsFiltered = this.options.filter((option, key) => {
            if (option.textContent.includes(value)) {
                return option;
            }
        });
        this.buildList(this.optionsFiltered);
    }


    /**
     * build list displaying
     *
     * @param options
     * @private
     */
    private buildList(options:HTMLOptionElement[])
    {
        this.logger.trace('buildList');
        this.listElement.innerHTML = ''
        options.forEach((ele, key) => {
            this.addItemList(ele, key);
        });
    }

    /**
     * Add item in list
     *
     * @param option
     * @param key
     * @private
     */
    private addItemList(option:HTMLOptionElement, key:number)
    {
        this.logger.trace('addItemList', key);
        const li:HTMLLIElement = this.platform.document.createElement('li');
        li.classList.add('list-option');
        li.setAttribute('role', 'option');
        li.setAttribute('id', 'option-'+key);
        li.setAttribute('data-id', option.value);
        li.setAttribute('data-index', key.toString());
        const itemFind = this.findChoiceItem(option.value);
        if (itemFind) {
            li.setAttribute('aria-selected', 'true');
        } else {
            li.setAttribute('aria-selected', 'false');
        }
        li.textContent = option.textContent;
        li.addEventListener('click', this.onItemClick);
        this.listElement.append(li);
    }

    /**
     * Focus to input search
     *
     * @param event
     */
    private readonly onFocus = (event:Event) => {
        this.logger.trace('onFocus');
        event.preventDefault();
        this.openList();
    }

    private readonly onFocusout = (event:Event) => {
        this.logger.trace('onFocusout');
        event.preventDefault();
        this.closeList();
    }

    /**
     * Manage keypress
     *
     * @param event
     */
    private readonly onKeydown = (event:KeyboardEvent) => {
        this.logger.trace('onKeydown', event);
        const key = event.key;
        if (this.availableKeyboard.includes(key)) {
            event.preventDefault();
            const total = this.listElement.children.length;
            if (!this.listOpen) {
                this.openList();
            }
            this.activeItemPrevIndex = this.activeItemNewIndex;
            switch (key) {
                case 'ArrowDown':
                    if (this.activeItemNewIndex >= total-1) {
                        this.activeItemNewIndex = -1;
                    }
                    this.activeItemNewIndex = Math.min(this.activeItemNewIndex+1, total-1);
                    this.logger.trace('ArrowDown', this.activeItemNewIndex, total, this.activeItemPrevIndex);
                    break;
                case 'ArrowUp':
                    if (this.activeItemNewIndex == 0) {
                        this.activeItemNewIndex = total;
                    }
                    this.activeItemNewIndex = Math.max(this.activeItemNewIndex-1, 0);
                    this.logger.trace('ArrowUp', this.activeItemNewIndex, total, this.activeItemPrevIndex);
                    break;
                case 'Enter': {
                    this.logger.trace('Enter', this.activeItemNewIndex, total, this.activeItemPrevIndex);
                    const item = this.findListItem(this.activeItemNewIndex);
                    this.manageItem(item);
                    break;
                }
                case 'Escape':
                    this.activeItemNewIndex = -1;
                    this.closeList();
                    this.inputSearch.focus();
                    break;
            }
            this.ariaActiveItem(this.activeItemNewIndex, true);
            this.ariaActiveItem(this.activeItemPrevIndex, false);
        }

    }


    /**
     * manage aria active
     *
     * @param index
     * @param active
     * @private
     */
    private ariaActiveItem(index:number, active:boolean)
    {
        this.logger.trace('activeItem');
        const liId = 'option-'+index;
        this.listElement.setAttribute('aria-activedescendant', liId);
        const item = this.findListItem(index);
        if(item) {
            if (active) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        }
    }

    /**
     * Notify message for accessibility
     *
     * @param msg
     * @private
     */
    private notification(msg:string)
    {
        this.platform.setTimeout(() => {
            this.divLiveMsg.textContent = msg;
        }, 50);
    }

    /**
     * Add new item choiced in div
     *
     * @param itemLi
     * @private
     */
    private addItem(itemLi:HTMLLIElement)
    {
        this.logger.trace('addItem');
        const span = this.platform.document.createElement('span');
        itemLi.setAttribute('aria-selected', 'true');
        span.setAttribute('data-id', itemLi.getAttribute('data-id'));
        span.classList.add('item');
        span.textContent = itemLi.textContent;
        const btnClose = this.platform.document.createElement('button');
        btnClose.classList.add('item-close');
        btnClose.role ='button';
        btnClose.textContent = 'X';
        btnClose.addEventListener('click', this.onRemoveItem);
        span.append(btnClose);
        const item:IItemChoice  = {
            value:itemLi.getAttribute('data-id'),
            content:itemLi.textContent
        };
        this.notification(itemLi.textContent+' ajouté');
        this.pushChoiceItem(item);
        this.inputSearch.before(span);
    }

    /**
     * Add choice item
     *
     * @param newItem
     * @private
     */
    private pushChoiceItem(newItem:IItemChoice)
    {
        this.logger.trace('pushChoiceItem');
        const find = this.findChoiceItem(newItem.value);
        if(!find) {
            this.currentChoiced.push(newItem);
        }
    }

    /**
     * Remove item choice
     *
     * @param value
     * @private
     */
    private removeChoiceItem(value:any)
    {
        this.logger.trace('removeChoiceItem');
        if (this.currentChoiced.length > 0) {
            const newItems:IItemChoice[] = [];
            this.currentChoiced.forEach((item:IItemChoice, key:number) => {
                if (item.value != value) {
                    newItems.push(item);
                }
            });
            this.currentChoiced = [...newItems];

        }
    }


    /**
     * Find  value in choice item list
     *
     * @param value
     * @private
     */
    private findChoiceItem(value:any) :IItemChoice
    {
        this.logger.trace('findChoiceItem');
        return this.currentChoiced.find((item:IItemChoice, index:number) => {
            return value == item.value;
        });
    }


    private findListItem(index:number) :HTMLLIElement | null
    {
        this.logger.trace('findChoiceItem');
        let item:HTMLLIElement;
        if (this.listElement) {
            item = this.listElement.children.item(index) as HTMLLIElement;
        }
        return item
    }


    /**
     * Remove item
     *
     * @param itemLi
     * @private
     */
    private removeItem(itemLi:HTMLLIElement)
    {
        this.logger.trace('removeItem');
        const dataItemId =  itemLi.getAttribute('data-id');
        const spans = this.divItemContainer.querySelectorAll('span');
        spans.forEach((span:HTMLSpanElement, key:number) => {
           const dataId =  span.getAttribute('data-id');
           if (dataId == dataItemId) {
               this.removeChoiceItem(dataId);
               span.remove();
               this.notification(itemLi.textContent+' retiré');
           }
        });
    }

    /**
     * Click on li from this list item
     *
     * @param event
     */
    private readonly onItemClick = (event:Event) => {
        this.logger.trace('onItemClick');
        event.preventDefault();
        const target = event.currentTarget as HTMLLIElement;
        this.manageItem(target);
        this.closeList();
    }

    private manageItem(item:HTMLLIElement)
    {
        this.logger.trace('manageItem');
        if (item) {
            const ariaSelected = item.getAttribute('aria-selected');
            if (ariaSelected == 'false') {
                this.addItem(item);
            } else {
                item.setAttribute('aria-selected', 'false');
                this.removeItem(item);
            }
            this.updateInputSelectElement();
        }
    }
    /**
     * Close list
     *
     * @private
     */
    private closeList()
    {
        this.listElement.style.display = 'none';
        if( this.inputSearch) {
            this.inputSearch.classList.add('input-search');
            this.inputSearch.classList.remove('input-search--focus');
        }
        this.listOpen = false;
    }


    /**
     * Open list
     *
     * @private
     */
    private openList()
    {
        this.listElement.style.display = 'block';
        if (this.inputSearch) {
            this.inputSearch.classList.remove('input-search');
            this.inputSearch.classList.add('input-search--focus');
        }
        this.listOpen = true;
    }


    /**
     * Remove item selected
     *
     * @param event
     */
    private readonly onRemoveItem = (event:Event) => {
        this.logger.trace('removeItem');
        event.preventDefault();
        const  current = event.currentTarget as HTMLElement;
        const target:HTMLSpanElement = current.closest('span.item');
        if (target) {
            this.unSelected(target);
            this.notification(target.firstChild.textContent+' retiré');
            target.remove();
        }
    }

    /**
     * Un selected item
     *
     * @param span
     * @private
     */
    private unSelected(span:HTMLSpanElement) {
        this.logger.trace('unSelected');
        const itemId = span.getAttribute('data-id');
        const selectedLis = this.listElement.querySelectorAll('li');
        selectedLis.forEach((li:HTMLLIElement, key:number) => {
            const liId = li.getAttribute('data-id');
            if (itemId == liId) {
                this.removeChoiceItem(liId);
                li.setAttribute('aria-selected', 'false');
            }
        });
    }

    /**
     * Update select value
     *
     * @private
     */
    private updateInputSelectElement()
    {
        this.logger.trace('updateInputSelectElement');
        this.options.forEach((option:HTMLOptionElement, index:number) => {
            option.removeAttribute('selected');
            this.currentChoiced.forEach((itemChoice:IItemChoice, key:number) => {
                if (option.value == itemChoice.value) {
                    option.setAttribute('selected', '');
                }
            });
        });
    }
}