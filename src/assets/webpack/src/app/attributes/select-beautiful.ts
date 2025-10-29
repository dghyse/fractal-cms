import {bindable, customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";

export interface IItem {
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
    private inputSearch:HTMLInputElement;
    private currentChoiced:IItem[] = [];

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
        this.init();
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
        this.element.style.display = 'none';
        this.element.querySelectorAll('option').forEach((option, key) => {
            this.options.push(option);
        });

        this.listElement = this.platform.document.createElement('ul');
        this.closeList();
        this.listElement.setAttribute('role', 'listbox');
        this.listElement.setAttribute('aria-multiselectable', 'true');
        this.listElement.classList.add('list-items-container')

        this.options.forEach((ele, key) => {
            this.addItemList(ele);
        });
        this.closeList()


        this.divContainer = this.platform.document.createElement('div');
        this.divContainer.classList.add('select-beautiful-container');


        this.inputSearch = this.platform.document.createElement('input');
        this.inputSearch.type = 'text';
        this.inputSearch.classList.add('input-search')
        this.inputSearch.name = this.searchInputName;
        this.inputSearch.setAttribute('autocomplete', 'off');
        this.inputSearch.placeholder = 'Rechercher';
        this.inputSearch.addEventListener('input', this.onSearch);
        this.inputSearch.addEventListener('focusin', this.onFocus);

        this.divItemContainer = this.platform.document.createElement('div');
        this.divItemContainer.classList.add('item-container');
        this.divItemContainer.append(this.inputSearch);

        this.divSearchContainer = this.platform.document.createElement('div');
        this.divSearchContainer.classList.add('search-container');
        this.divSearchContainer.append(this.listElement);

        this.element.before(this.divItemContainer);
        this.element.before(this.divSearchContainer);
    }


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


    private buildList(options:HTMLOptionElement[])
    {
        this.logger.trace('buildList');
        this.listElement.innerHTML = ''
        options.forEach((ele, key) => {
            this.addItemList(ele);
        });
    }

    private addItemList(option:HTMLOptionElement)
    {
        this.logger.trace('addItemList');
        const li:HTMLLIElement = this.platform.document.createElement('li');
        li.classList.add('list-option');
        li.setAttribute('role', 'radio');
        li.setAttribute('data-id', option.value);
        const itemFind = this.findChoiceIitem(option.value);
        if (itemFind) {
            li.setAttribute('aria-selected', 'true');
        } else {
            li.setAttribute('aria-selected', 'false');
        }
        li.textContent = option.textContent;
        li.addEventListener('click', this.onItemClick);
        this.listElement.append(li);
    }

    private readonly onFocus = (event:Event) => {
        this.logger.trace('onFocus');
        event.preventDefault();
        this.openList();
    }

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
        const item:IItem  = {
            value:itemLi.getAttribute('data-id'),
            content:itemLi.textContent
        };
        this.pushChoiceIitem(item);
        this.inputSearch.before(span);
    }

    private pushChoiceIitem(newItem:IItem)
    {
        this.logger.trace('pushIitem');
        let hasItem:boolean = false;
        this.currentChoiced.forEach((item:IItem, key:number) => {
            if (item.value == newItem.value) {
                hasItem = true;
                return hasItem;
            }
        });
        if( hasItem === false) {
            this.currentChoiced.push(newItem);
        }
    }
    private removeChoiceIitem(value:any)
    {
        this.logger.trace('removeChoiceIitem');
        if (this.currentChoiced.length > 0) {
            const newItems:IItem[] = [];
            this.currentChoiced.forEach((item:IItem, key:number) => {
                if (item.value != value) {
                    newItems.push(item);
                }
            });
            this.currentChoiced = [...newItems];

        }
    }

    private findChoiceIitem(value:any) :IItem
    {
        this.logger.trace('removeChoiceIitem');
        return this.currentChoiced.find((item:IItem, index:number) => {
            return value == item.value;
        });
    }


    private removeItem(itemLi:HTMLLIElement)
    {
        this.logger.trace('removeItem');
        const dataItemId =  itemLi.getAttribute('data-id');
        const spans = this.divItemContainer.querySelectorAll('span');
        spans.forEach((span:HTMLSpanElement, key:number) => {
           const dataId =  span.getAttribute('data-id');
           if (dataId == dataItemId) {
               this.removeChoiceIitem(dataId);
               span.remove();
           }
        });
    }


    private readonly onItemClick = (event:Event) => {
        this.logger.trace('onItemClick');
        event.preventDefault();
        const target = event.currentTarget as HTMLLIElement;
        const ariaSelected = target.getAttribute('aria-selected');
        if (ariaSelected == 'false') {
            this.addItem(target);
        } else {
            target.setAttribute('aria-selected', 'false');
            this.removeItem(target);
        }
        this.closeList();
    }

    private closeList()
    {
        this.listElement.style.display = 'none';
        if( this.inputSearch) {
            this.inputSearch.classList.add('input-search');
            this.inputSearch.classList.remove('input-search--focus');
        }
    }


    private openList()
    {
        this.listElement.style.display = 'block';
        if (this.inputSearch) {
            this.inputSearch.classList.remove('input-search');
            this.inputSearch.classList.add('input-search--focus');
        }
    }


    private readonly onRemoveItem = (event:Event) => {
        this.logger.trace('removeItem');
        event.preventDefault();
        const  current = event.currentTarget as HTMLElement;
        const target:HTMLSpanElement = current.closest('span.item');
        if (target) {
            this.unSelected(target);
            target.remove();
        }
    }

    private unSelected(span:HTMLSpanElement) {
        this.logger.trace('unSelected');
        const itemId = span.getAttribute('data-id');
        const selectedLis = this.listElement.querySelectorAll('li');
        selectedLis.forEach((li:HTMLLIElement, key:number) => {
            const liId = li.getAttribute('data-id');
            if (itemId == liId) {
                this.removeChoiceIitem(liId);
                li.setAttribute('aria-selected', 'false');
            }
        });
    }


}