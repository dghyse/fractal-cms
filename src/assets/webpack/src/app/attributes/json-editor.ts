import {bindable, customAttribute, ILogger, INode, resolve, IPlatform} from "aurelia";
import {ApiServices} from "../services/api-services";
import JSONEditor, {JSONEditorOptions} from "jsoneditor";

@customAttribute('cms-json-editor')
export class JsonEditor {

    @bindable() fieldSelector: string;
    private editor:JSONEditor;
    private template:HTMLElement;
    private inputHidden:HTMLInputElement;
    private initialSchema:string;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('JsonEditor'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly apiService: ApiServices = resolve(ApiServices),
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
    }

    public attached()
    {
        this.logger.trace('attached');
        this.template = this.element.querySelector('.jsonEditor');
        this.inputHidden = this.element.querySelector('.jsonInput');
        if (this.inputHidden && this.inputHidden.value !== '' && this.inputHidden.value) {
            this.initialSchema = JSON.parse(this.inputHidden.value);
        }
        this.buildEditor();
    }
    public detached()
    {
        this.logger.trace('attached');
    }

    private buildEditor()
    {
        const options: JSONEditorOptions = {
            mode: "tree",
            modes: ["tree", "text"],
            search: false,
            navigationBar: false,
            statusBar: true,
            mainMenuBar: true,
            enableSort: false,
            enableTransform: false
        };
        if (this.template) {
            //@ts-ignore
            options.onChange = () => {
                const jsonData = this.editor.get();
                this.inputHidden.value = JSON.stringify(jsonData);
            }
            if (this.template) {
                this.editor = new JSONEditor(this.template, options);
                let initial:any = this.initialSchema;
                if (!this.initialSchema) {
                    initial = {
                        title: {
                            type:"string",
                            title:"Titre"
                        },
                        choix: {
                            type:"radio",
                            title:"Choix",
                            values:[
                                {
                                    name:"Externe",
                                    value:"extern"
                                },
                                {
                                    name:"Interne",
                                    value:"interne"
                                }
                            ]
                        },
                    };
                }
                this.inputHidden.value = JSON.stringify(initial);
                this.editor.set(initial);
            }
        }

    }
}