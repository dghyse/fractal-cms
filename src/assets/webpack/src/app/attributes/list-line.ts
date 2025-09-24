import {bindable, customAttribute, ILogger, INode, resolve, IPlatform} from "aurelia";
import {ApiServices} from "../services/api-services";

@customAttribute('cms-list-line')
export class ListLine {

    @bindable() id: number;

    private buttonDelete:HTMLLinkElement;

    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('ListLine'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
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
        this.buttonDelete = this.element.querySelector('.user-button-delete');
        if (this.buttonDelete) {
            this.buttonDelete.addEventListener('click', this.onDelete);
        }
    }

    private removeEvent()
    {
        this.logger.trace('removeEvent');
        if (this.buttonDelete) {
            this.buttonDelete.removeEventListener('click', this.onDelete);
        }

    }

    public onDelete = (event:Event) => {
        this.logger.trace('onDelete');
        event.preventDefault();
        if (this.buttonDelete) {
            if (confirm('Attention !! vous allez supprimer définitivement cet utilisateur') === true) {
                const href:string = this.buttonDelete.href;
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

    public onValidate(event:Event) {
        this.logger.trace('onValidate');
        return true;
    }
}