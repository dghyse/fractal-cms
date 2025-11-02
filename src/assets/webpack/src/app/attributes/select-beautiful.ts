import {bindable, customAttribute, ILogger, INode, resolve, IPlatform} from "aurelia";

export interface IItemChoice {
    value:any;
    content:string
}
export enum ECssTheme {
    DEFAULT = 'default',
    DARK = 'dark',
    RED = 'red',
    BLUE = 'blue',
    SOFT = 'soft',
    CUSTOM = 'custom',
    GREEN = 'green',
}

export interface ISelectBeautifulOptions {
    multiple?: boolean;
    searchPlaceholder?: string;
    searchInputName?: string;
    iconButtonDelete?: string;
    removeText?: string;
    addText?: string;
    removeAllText?: string;
    eventChangeItemName?: string;
    theme?: ECssTheme;
}

export class SelectBeautifulOptions implements ISelectBeautifulOptions {
    public multiple = true;
    public searchPlaceholder = 'Rechercher';
    public searchInputName = 'model[search]';
    public iconButtonDelete: any;
    public removeText = 'retiré';
    public removeAllText = 'Toutes les sélections ont été supprimées';
    public addText = 'ajouté';
    public eventChangeItemName = 'fractalcms-select-change';
    public theme: ECssTheme = ECssTheme.DEFAULT;

    constructor(options?: Partial<ISelectBeautifulOptions>) {
        Object.assign(this, options);
    }
}

@customAttribute('fractalcms-select-beautiful')
export class SelectBeautiful {

    @bindable({primary:true}) bindableOptions: SelectBeautifulOptions = new SelectBeautifulOptions();
    private listElement?:HTMLUListElement;
    private readonly options:HTMLOptionElement[];
    private optionsFiltered?:HTMLOptionElement[];
    private divContainer?:HTMLDivElement;
    private divSearchContainer?:HTMLDivElement;
    private divItemContainer?:HTMLDivElement;
    private divLiveMsg?:HTMLDivElement;
    private inputSearch?:HTMLInputElement;
    private currentChoiced:IItemChoice[] = [];
    private listOpen:boolean = false;
    private activeItemNewIndex:number = -1;
    private activeItemPrevIndex?:number;
    private readonly availableKeyboard = [
        'ArrowDown',
        'ArrowUp',
        'Enter',
        'Escape',
    ];
    private timeoutId?:number;
    private divListItemId?:string;

    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('SelectBeautiful'),
        private readonly element: HTMLSelectElement = resolve(INode) as HTMLSelectElement,
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
        this.options = [] as HTMLOptionElement[];
    }


    public attached()
    {
        this.logger.trace('attached');
        if (!this.divListItemId) {
            this.divListItemId = 'list-item-ul'+Math.random().toString(36).slice(2, 8);
        }
        this.bindableOptions = this.setOptions(this.bindableOptions);
        this.initStructure();
        this.buildList(this.options);
        this.closeList();
        this.restoreSelect();
    }

    /**
     * set default options
     *
     * @param options
     * @private
     */
    private setOptions(options: Partial<SelectBeautifulOptions> = this.bindableOptions): SelectBeautifulOptions {
        const defaults = new SelectBeautifulOptions();
        const merged = Object.assign({}, defaults, options);
        return merged as SelectBeautifulOptions;
    }

    public detached()
    {
        this.logger.trace('attached');
        this.platform.document.removeEventListener('focusin', this.onFocusinDom, true);
        this.platform.document.removeEventListener('pointerdown', this.onPointerdown, true);
        if(this.inputSearch) {
            this.inputSearch.removeEventListener('input', this.onSearch);
            this.inputSearch.removeEventListener('focusin', this.onFocusin);
            this.inputSearch.removeEventListener('keydown', this.onKeydown);
        }
        if (this.listElement) {
            this.listElement.removeEventListener('click', this.onListItemClick);
        }

        if (this.divItemContainer) {
            this.divItemContainer.querySelectorAll('button').forEach((button:HTMLButtonElement, index:number) => {
                button.removeEventListener('click', this.onRemoveItem);
            });
        }
        if (this.timeoutId) {
            this.platform.clearTimeout(this.timeoutId);
        }

    }

    /**
     * Init structure
     *
     * @private
     */
    private initStructure()
    {
        this.logger.trace('initStructure');
        this.element.setAttribute('multiple', 'true');
        const listLabel = this.element.getAttribute('prompt');
        this.element.style.display = 'none';
        //Store select options
        this.element.querySelectorAll('option').forEach((option, key) => {
            if (option.value) {
                this.options.push(option);
                if (option.selected) {
                    const itemChoice:IItemChoice = {
                        value:option.value,
                        content:option.textContent
                    };
                    this.currentChoiced.push(itemChoice);
                }
            }

        });
        //Create div container
        this.divContainer = this.platform.document.createElement('div');
        this.divContainer.classList.add('theme-'+this.bindableOptions.theme, 'select-beautiful');
        //Create item container
        this.divItemContainer = this.platform.document.createElement('div');
        this.divItemContainer.classList.add('select-beautiful--item');
        //Search container
        this.divSearchContainer = this.platform.document.createElement('div');
        this.divSearchContainer.classList.add('select-beautiful--search');
        //Create ul list item
        this.listElement = this.platform.document.createElement('ul');
        this.listElement.setAttribute('role', 'listbox');
        if (listLabel) {
            this.listElement.setAttribute('aria-label', listLabel);
        } else {
            this.listElement.setAttribute('aria-label', this.bindableOptions.searchPlaceholder);
        }
        this.listElement.setAttribute('aria-multiselectable', 'true');
        this.listElement.setAttribute('tabindex', '0');
        this.listElement.classList.add('select-beautiful--search---list--items')
        if (this.divListItemId) {
            this.listElement.setAttribute('id', this.divListItemId);
        }
        this.listElement.addEventListener('click', this.onListItemClick);
        //Create input search
        this.inputSearch = this.platform.document.createElement('input');
        this.inputSearch.type = 'text';
        this.inputSearch.classList.add('select-beautiful--search---input')
        this.inputSearch.name = this.bindableOptions.searchInputName;
        this.inputSearch.setAttribute('autocomplete', 'off');
        this.inputSearch.placeholder = this.bindableOptions.searchPlaceholder;
        this.inputSearch.setAttribute('aria-label', this.bindableOptions.searchPlaceholder);
        if (this.divListItemId) {
            this.inputSearch.setAttribute('aria-controls', this.divListItemId);
        }
        this.inputSearch.setAttribute('role', 'combobox');
        this.inputSearch.setAttribute('aria-expanded', 'false');
        this.inputSearch.addEventListener('input', this.onSearch);
        this.inputSearch.addEventListener('focusin', this.onFocusin);
        this.inputSearch.addEventListener('keydown', this.onKeydown);
        this.divSearchContainer.append(this.inputSearch);
        this.divSearchContainer.append(this.listElement);

        //Create div live message
        this.divLiveMsg = this.platform.document.createElement('div');
        this.divLiveMsg.setAttribute('aria-live','polite');
        this.divLiveMsg.classList.add('sr-only');
        this.divContainer.append(this.divItemContainer, this.divSearchContainer, this.divLiveMsg);
        //Append element in Dom
        this.element.before(this.divContainer);
    }

    private restoreSelect()
    {
        this.logger.trace('restoreSelect');
        //Add selected item
        if (this.listElement) {
            this.listElement.querySelectorAll('li').forEach((li:HTMLLIElement, key:number) => {
                const selected = li.getAttribute('aria-selected');
                if (selected == 'true') {
                    this.addItem(li);
                }
            });
        }
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
        if(this.listElement) {
            this.listElement.innerHTML = ''
        }
        if (options.length) {
            this.activeItemNewIndex = -1;
        }
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
        li.classList.add('select-beautiful--search---list--items---option');
        li.setAttribute('role', 'option');
        li.setAttribute('id', 'option-'+key);
        li.setAttribute('data-id', option.value);
        li.setAttribute('data-index', key.toString());
        const itemFind = this.findInChoiceItem(option.value);
        if (itemFind) {
            li.setAttribute('aria-selected', 'true');
        } else {
            li.setAttribute('aria-selected', 'false');
        }
        li.textContent = option.textContent;
        if(this.listElement) {
            this.listElement.append(li);
        }
    }

    /**
     * Focus to input search
     *
     * @param event
     */
    private readonly onFocusin = (event:Event) => {
        this.logger.trace('onFocusin');
        this.openList();
    }

    /**
     * Focus out container
     *
     * @param event
     */
    private readonly onFocusinDom = (event:Event) => {
        const target = event.target as Node;
        this.logger.trace('onFocusinDom', target);
        if (this.divContainer && !this.divContainer.contains(target) && this.listOpen) {
            this.closeList();
        }
    }

    private readonly onPointerdown = (event:Event) => {
        const target = event.target as Node;
        this.logger.trace('onPointerdown', target);
        if (this.divContainer && !this.divContainer.contains(target) && this.listOpen) {
            this.closeList();
        }
    }

    /**
     * Search in option
     *
     * @param event
     */
    private readonly onSearch = (event:Event) => {
        this.logger.trace('onSearch');
        event.preventDefault();
        if (!this.listOpen) {
            this.openList();
        }
        const target = event.currentTarget as HTMLInputElement;
        const value = target.value.trim().toLowerCase();
        this.optionsFiltered = this.options.filter((option, key) => {
            return option.textContent?.toLowerCase().includes(value);
        });
        this.buildList(this.optionsFiltered);
    }

    /**
     * Click on li from this list item
     *
     * @param event
     */
    private readonly onListItemClick = (event:Event) => {
        this.logger.trace('onListItemClick');
        event.preventDefault();
        const target = event.target as HTMLElement;
        const item = target.closest('li');
        this.manageItem(item);
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
            const total = (this.listElement) ? this.listElement.children.length : 0;
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
                    const item = this.findInListItem(this.activeItemNewIndex);
                    this.manageItem(item);
                    break;
                }
                case 'Escape':
                    this.activeItemNewIndex = -1;
                    this.closeList();
                    if (this.inputSearch) {
                        this.inputSearch.focus();
                    }
                    break;
            }
            this.ariaActiveItem(this.activeItemNewIndex, true);
            this.ariaActiveItem(this.activeItemPrevIndex, false);
        }
    }

    /**
     * Remove item selected
     *
     * @param event
     */
    private readonly onRemoveItem = (event:Event) => {
        this.logger.trace('onRemoveItem');
        event.preventDefault();
        const  current = event.currentTarget as HTMLElement;
        const target:HTMLSpanElement | null = current.closest('span');
        if (target) {
            const firstChild = target.querySelector(':not(button)');
            let textContent = target.firstChild?.textContent;
            if (firstChild) {
                textContent = firstChild.textContent;
            }
            this.notification(textContent+' '+this.bindableOptions.removeText);
            this.removeAndUnSelected(target);
        }
    }


    /**
     * Dispatch event change
     *
     * @private
     */
    private dispatchChangeEvent() {
        const event = new CustomEvent(this.bindableOptions.eventChangeItemName, {
            detail: this.currentChoiced,
            bubbles: true
        });
        this.element.dispatchEvent(event);
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
        const item = this.findInListItem(index);
        if(item) {
            if (active) {
                item.classList.add('active');
                if (this.inputSearch) {
                    this.inputSearch.setAttribute('aria-activedescendant', liId);
                }
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
        this.timeoutId = this.platform.setTimeout(() => {
            if (this.divLiveMsg) {
                this.divLiveMsg.textContent = msg;
            }
        }, 50);
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
        const find = this.findInChoiceItem(newItem.value);
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
    private findInChoiceItem(value:any) :IItemChoice | undefined
    {
        this.logger.trace('findInChoiceItem');
        return this.currentChoiced.find((item:IItemChoice, index:number) => {
            return value == item.value;
        });
    }


    private findInListItem(index:number) :HTMLLIElement | null
    {
        this.logger.trace('findInListItem');
        let item:HTMLLIElement | null = null;
        if (this.listElement) {
            item = this.listElement.children.item(index) as HTMLLIElement;
        }
        return item
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
        const dataId = itemLi.getAttribute('data-id');
        if( dataId) {
            span.setAttribute('data-id', dataId);
        }
        span.classList.add('select-beautiful--item---item');
        span.textContent = itemLi.textContent;
        const btnClose = this.platform.document.createElement('button');
        btnClose.classList.add('select-beautiful--item---item-close');
        btnClose.role ='button';
        btnClose.textContent = 'X';
        btnClose.addEventListener('click', this.onRemoveItem);
        span.append(btnClose);
        const item:IItemChoice  = {
            value:itemLi.getAttribute('data-id'),
            content:itemLi.textContent
        };
        this.notification(itemLi.textContent+' '+this.bindableOptions.addText);
        this.pushChoiceItem(item);
        if (this.divItemContainer) {
            this.divItemContainer.append(span);
        }
    }

    /**
     * Remove and unselect item
     *
     * @param element
     * @private
     */
    private removeAndUnSelected(element:HTMLElement) {
        this.logger.trace('removeAndUnSelected');
        const itemId = element.getAttribute('data-id');
        if (this.listElement) {
            this.listElement.querySelector(`[data-id="${itemId}"]`)?.setAttribute('aria-selected', 'false');
        }
        if (this.divItemContainer) {
            this.divItemContainer.querySelector(`[data-id="${itemId}"]`)?.remove();
        }
        this.removeChoiceItem(itemId);
    }


    /**
     * Manage item
     *
     * @param item
     * @private
     */
    private manageItem(item:HTMLLIElement | null)
    {
        this.logger.trace('manageItem');
        if (item) {
            const ariaSelected = item.getAttribute('aria-selected');
            if (ariaSelected == 'false') {
                if (!this.bindableOptions.multiple && this.currentChoiced.length > 0) {
                    this.clearAll();
                }
                this.addItem(item);
            } else {
                this.notification(item.textContent+' '+this.bindableOptions.removeText);
                this.removeAndUnSelected(item)
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
        if (this.listElement) {
            this.listElement.style.display = 'none';
            this.listElement.setAttribute('aria-hidden', 'true');
        }
        if (this.listOpen) {
            if( this.inputSearch) {
                this.inputSearch.classList.add('select-beautiful--search---input');
                this.inputSearch.classList.remove('select-beautiful--search---input--focus');
                this.inputSearch.setAttribute('aria-expanded', 'false');
            }
            this.platform.document.removeEventListener('focusin', this.onFocusinDom, true);
            this.platform.document.removeEventListener('pointerdown', this.onPointerdown, true);
            this.listOpen = false;
        }
    }


    /**
     * Open list
     *
     * @private
     */
    private openList()
    {
        if (this.listElement) {
            this.listElement.style.display = 'block';
            this.listElement.setAttribute('aria-hidden', 'false');
        }
        if (!this.listOpen) {
            if (this.inputSearch) {
                this.inputSearch.classList.remove('select-beautiful--search---input');
                this.inputSearch.classList.add('select-beautiful--search---input--focus');
                this.inputSearch.setAttribute('aria-expanded', 'true');
            }
            this.platform.document.addEventListener('focusin', this.onFocusinDom, true);
            this.platform.document.addEventListener('pointerdown', this.onPointerdown, true);
            this.listOpen = true;
        }
    }


    /**
     * Update select value
     *
     * @private
     */
    private updateInputSelectElement() : void
    {
        this.logger.trace('updateInputSelectElement');
        const selectedValues = new Set(this.currentChoiced.map(i => i.value));
        this.options.forEach((option:HTMLOptionElement, index:number) => {
            option.removeAttribute('selected');
            if (selectedValues.has(option.value)) {
                option.setAttribute('selected', '');
            }
        });
        this.dispatchChangeEvent();
    }

    /**
     * Clear all
     *
     * @private
     */
    private clearAll(verbose:boolean = false) : void
    {
        this.logger.trace('clearAll');
        this.currentChoiced = [];
        if (this.divItemContainer) {
            this.divItemContainer.innerHTML = '';
        }
        if (this.listElement) {
            this.listElement.querySelectorAll('[aria-selected="true"]').forEach(li => {
                li.setAttribute('aria-selected', 'false');
            });
        }
        if(verbose) {
            this.notification(this.bindableOptions.removeAllText);
        }
        this.updateInputSelectElement();
    }
}