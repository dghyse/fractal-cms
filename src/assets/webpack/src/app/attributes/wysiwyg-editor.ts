import {bindable, customAttribute, ILogger, INode, resolve, IPlatform} from "aurelia";
import {ApiServices} from "../services/api-services";
import Quill, {QuillOptions} from "quill";
import hljs from 'highlight.js';
import {Element} from "chart.js";
@customAttribute('cms-wysiwyg-editor')
export class WysiwygEditor {

    @bindable() options:QuillOptions = {
        theme: 'snow',
        placeholder: 'votre texte ici ...',
        modules: {
            syntax: { hljs },
            toolbar: [
                [{ header: [2, 3, 4, false] }],
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
            const value = this.blockCodeRestore(this.inputHidden.value);

            this.quill.root.innerHTML = value;
        }
        this.quill.on('text-change', () => {
            this.logger.trace('text-change',this.quill.root.innerHTML);
            const newHtml = this.blockCodeClean(this.quill.root.innerHTML);
            this.inputHidden.value = newHtml;
        });
    }

    /**
     * document.querySelectorAll('.ql-code-block-container').forEach(container => {
     *   let lang = 'plaintext';
     *   const lines = [];
     *
     *   container.querySelectorAll('.ql-code-block').forEach(line => {
     *     if (line.dataset.language) {
     *       lang = line.dataset.language;
     *     }
     *     lines.push(line.textContent);
     *   });
     *
     *   const codeElement = document.createElement('code');
     *   codeElement.className = 'language-' + lang;
     *   codeElement.textContent = lines.join('\n');
     *
     *   const pre = document.createElement('pre');
     *   pre.appendChild(codeElement);
     *
     *   container.replaceWith(pre);
     * });
     *
     * const parser = new DOMParser();
     * const doc = parser.parseFromString(htmlString, 'text/html');
     *
     * // Maintenant tu peux manipuler :
     * const div = doc.body.firstChild;
     * console.log(div.querySelector('p').textContent); // "Hello"
     * @param innerHtml
     * @private
     */
    private blockCodeClean(innerHtml:string)
    {
        this.logger.trace('manageBlockCode');
        let newHtml = innerHtml;
        if (innerHtml) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(innerHtml, 'text/html');
            const containers:NodeListOf<HTMLElement> = doc.querySelectorAll('.ql-code-block-container');
            containers.forEach((container:HTMLElement, index:number) => {
                const lines:NodeListOf<HTMLElement> = container.querySelectorAll('.ql-code-block');
                let lang : string | null = null;
                let textLines:string[] = [];
                lines.forEach((ele:HTMLElement, index:number) => {
                    if (lang == null && ele.hasAttribute('data-language')) {
                        lang = ele.getAttribute('data-language');
                    }
                    textLines.push(ele.textContent);
                });
                const pre = this.platform.document.createElement('pre');
                const code = this.platform.document.createElement('code');
                code.classList.add('language-'+lang);
                code.textContent = textLines.join("\n");
                pre.append(code);
                //container.remove();
                container.replaceWith(pre);
                //doc.body.append(pre);

                newHtml = doc.body.innerHTML;
            });
        }
        return newHtml;
    }

    private blockCodeRestore(innerHtml:string)
    {
        this.logger.trace('blockCodeRestore');
        let newHtml = innerHtml;
        if (innerHtml) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(innerHtml, 'text/html');
          //  const pre = doc.querySelector('pre');
            const pres:NodeListOf<HTMLElement> = doc.querySelectorAll('pre');
            pres.forEach((pre:HTMLElement, index:number) => {
                const code = pre.querySelector('code');
                if (code) {
                    let langClass : string | null = null;
                    code.classList.forEach((value :string, key) => {
                        if (value.startsWith('language-')) {
                            langClass = value;
                        }
                    });
                    const lang = langClass ? langClass.replace('language-', '') : 'plain';
                    const container = this.platform.document.createElement('div');
                    container.classList.add('ql-code-block-container');
                    container.setAttribute('spellcheck', 'false');

                    // Simule le select de Quill (optionnel, si tu veux le rendre identique à ce que Quill génère)
                    const select = doc.createElement('select');
                    select.classList.add('ql-ui');
                    select.setAttribute('contenteditable', 'false');
                    const opt = doc.createElement('option');
                    opt.value = lang;
                    opt.textContent = lang.charAt(0).toUpperCase() + lang.slice(1);
                    select.appendChild(opt);
                    container.appendChild(select);



                    const lines = code.textContent.split("\n");
                    lines.forEach((value:string, key:number) => {
                        const div = this.platform.document.createElement('div');
                        div.classList.add('ql-code-block');
                        div.setAttribute('data-language', lang);
                        div.textContent = value;
                        container.appendChild(div);
                    })
                    pre.replaceWith(container);
                    newHtml = doc.body.innerHTML;
                }
            });
        }
        return newHtml;
    }
}