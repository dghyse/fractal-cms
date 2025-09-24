import {bindable, customAttribute, ILogger, INode, resolve} from "aurelia";

@customAttribute('cms-check-rules')
export class CheckRules {

    @bindable() id: number;

    private mainCheck:HTMLInputElement;
    private childCheck:NodeListOf<HTMLInputElement>;

    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('CheckRules'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement
    ) {
        this.logger.trace('constructor');
    }

    public attached()
    {
        this.logger.trace('attached');
        //Find sub elements
        this.mainCheck = this.element.querySelector('input.main-check');
        this.childCheck = this.element.querySelectorAll('input.sub-check');
        if (this.mainCheck) {
            this.mainCheck.addEventListener('click', this.onCheckMain);
        }
        if (this.childCheck) {
            this.childCheck.forEach((ele:HTMLInputElement, key:number)=> {
                ele.addEventListener('click', this.onCheckChild);
            });
        }
    }

    public detached()
    {
        this.logger.trace('detached');
        //Find sub elements
        if (this.mainCheck) {
            this.mainCheck.removeEventListener('click', this.onCheckMain);
        }
        if (this.childCheck) {
            this.childCheck.forEach((ele:HTMLInputElement, key:number)=> {
                ele.removeEventListener('click', this.onCheckChild);
            });
        }
    }

    public onCheckMain = (event:Event) => {
        this.logger.trace('onCheckMain');
        if (this.mainCheck) {
            if (this.childCheck) {
                this.childCheck.forEach((ele:HTMLInputElement, key:number)=> {
                    ele.checked = this.mainCheck.checked;
                });
            }
            return true;
        }
    }

    public onCheckChild = (event:Event) => {
        this.logger.trace('onCheckMain');
        const input:HTMLInputElement = <HTMLInputElement>event.currentTarget;
        if (input) {
            if (!input.checked) {
                if (this.mainCheck) {
                    this.mainCheck.checked = false;
                }
            }
            return true;
        }
    }

}