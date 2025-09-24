import {bindable, customAttribute, ILogger, INode, resolve, IPlatform} from "aurelia";
import {ApiServices} from "../services/api-services";
import Quill, {QuillOptions} from "quill";

@customAttribute('cms-wysiwyg-editor')
export class WysiwygEditor {

    @bindable() options:QuillOptions = {
        theme: 'snow',
        placeholder: 'votre texte ici ...',
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    };
    @bindable() inputId:string;
    private quill:Quill;
    private inputHidden:HTMLInputElement;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('WysiwygEditor'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly apiService: ApiServices = resolve(ApiServices),
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
    }

    public attached()
    {
        this.logger.trace('attached');
        this.inputHidden = this.platform.document.querySelector('#'+this.inputId);
        this.buildEditor();
    }
    public detached()
    {
        this.logger.trace('attached');
    }

    private buildEditor()
    {
        this.quill = new Quill(this.element, this.options);
        if (this.inputHidden && this.inputHidden.value) {
            this.quill.root.innerHTML = this.inputHidden.value;
        }
        this.quill.on('text-change', () => {
            this.logger.trace('text-change',this.quill.root.innerHTML);
            this.inputHidden.value = this.quill.root.innerHTML;
        });
    }
}