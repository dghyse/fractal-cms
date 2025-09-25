import {IEventAggregator, INode, ILogger, IDisposable, IPlatform, customElement, bindable, resolve} from 'aurelia';
import Resumable from "resumablejs"
import URI from 'urijs';
import {EApi} from "../enums/api";
import {EEvents} from "../enums/events";
import {ApiServices} from "../services/api-services";
import {ConfigService} from "../services/config-service";
interface UploadedFile {
    name:string,
    shortname:string|undefined,
    previewUrl:string,
    deleteUrl:string,
    file?:Resumable.ResumableFile|null
}
@customElement('cms-file-upload')
export class File
{
    private resumable:Resumable;
    private browse:HTMLDivElement;
    private drop:HTMLDivElement;
    public hiddenField:HTMLInputElement;
    private handledFiles:UploadedFile[];
    public fileInfo: string;
    public previewUrl: string = EApi.IMPORT_ASYNC_PREVIEW;
    public deleteUrl: string = EApi.IMPORT_ASYNC_DELETE;
    private subscriptionFileEnd: IDisposable;

    @bindable() public fileType: string = 'xls,xlsx,csv,zip,TXT,txt, png,jpeg,jpg,gif';
    @bindable() public name: string;
    @bindable() public multiple: string|boolean = false;
    @bindable() public value: string = '';
    @bindable() public imageWidth: string;
    @bindable() public imageHeight: string;
    @bindable() public title: string;
    @bindable() public uploadFileText: string = 'Télécharger un fichier';
    @bindable() public uploadFileDnd: string;
    @bindable() public uploadFileDescription: string;
    @bindable() public error: boolean = false;

    public constructor(
        private readonly logger: ILogger = resolve(ILogger),
        private readonly platform: IPlatform = resolve(IPlatform),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly apiServices: ApiServices = resolve(ApiServices),
        private readonly configService : ConfigService = resolve(ConfigService),
        private readonly ea: IEventAggregator = resolve(IEventAggregator)
    ) {
        this.logger = logger.scopeTo('File');
        this.logger.trace('constructor');
    }

    public attached() {
        this.logger.trace('attached');
        this.subscriptionFileEnd = this.ea.subscribe(EEvents.ACTION_FILE_END, this.onFileEnd);
        this.setUp();
    }

    public dispose() {
        this.logger.trace('dispose');
        this.subscriptionFileEnd.dispose();
    }
    public detached() {
        this.logger.trace('detaching');
        if (this.resumable.support) {
            this.drop.removeEventListener('dragover', this.onDragEnter);
            this.drop.removeEventListener('dragenter', this.onDragEnter);
            this.drop.removeEventListener('dragleave', this.onDragLeave);
            this.drop.removeEventListener('drop', this.onDragLeave);
        }
    }

    private onFileEnd = () => {
        this.logger.trace('onFileEnd');
        if (this.resumable.support) {
            this.drop.removeEventListener('dragover', this.onDragEnter);
            this.drop.removeEventListener('dragenter', this.onDragEnter);
            this.drop.removeEventListener('dragleave', this.onDragLeave);
            this.drop.removeEventListener('drop', this.onDragLeave);
        }
        this.setUp();
    }


    public onRemove(handledFile:UploadedFile, evt:Event)
    {
        evt.stopPropagation();
        evt.preventDefault();
        this.logger.debug('Should remove file', handledFile);
        let fileIndex:number|null = null;
        this.handledFiles.forEach((file:UploadedFile, index:number) => {
            if (handledFile.name === file.name) {
                fileIndex = index;
            }
        });
        if (fileIndex !== null && fileIndex >= 0) {
            if (handledFile.file && handledFile.file !== null) {
                this.resumable.removeFile(handledFile.file);
            }
            this.handledFiles.splice(fileIndex, 1);
           this.apiServices.delete(handledFile.deleteUrl);

            // should call WS delete
        }
        let fieldValue = this.getFilesValue();
        this.hiddenField.value = fieldValue;
    }


    private appendFile(name:string, file:Resumable.ResumableFile|null = null)
    {
        this.handledFiles.push({
            name: name,
            shortname: name.split(/.*[\/|\\]/).pop(),
            previewUrl: this.generatePreviewUrl(name),
            deleteUrl: this.generateDeleteUrl(name),
            file: file
        });
        this.hiddenField.value = this.getFilesValue();

    }

    private appendParametersUrl(url:string)
    {
        let baseUrl = new URI(url);
        return baseUrl.toString();
    }

    private generatePreviewUrl(name:string)
    {
        const url = this.configService.getApiBaseUrl()+this.previewUrl.replace('__name__', name);
        const fullUrl:string = url;
        return this.appendParametersUrl(fullUrl);
    }

    private generateDeleteUrl(name:string)
    {
        return this.configService.getApiBaseUrl()+this.deleteUrl.replace('__name__', name);
    }
    protected getFilesValue()
    {
        let mapped = this.handledFiles.map((uploadedFile: UploadedFile, index:number) => {
            return uploadedFile.name;
        }).join(', ');

        return (typeof mapped === 'string') ? mapped : '';
    }

    private setUp(): void {
        this.logger.debug('setUp');
        const UploadUrl =  this.configService.getApiBaseUrl()+EApi.IMPORT_ASYNC_UPLOAD;
        let resumableConfig:Resumable.ConfigurationHash = {
            target: UploadUrl,
            chunkSize: 512 * 1024
        };
        let fileTypes = this.fileType.split(/\s*,\s*/).filter((value:string, index:number) => {
            return value.trim() !== '';
        });
        this.fileInfo = fileTypes.map((item:string) => {return item.toLocaleUpperCase(); }).join(', ');
        resumableConfig.fileType = fileTypes;
        this.hiddenField = this.platform.document.createElement('input');
        this.hiddenField.type = 'hidden';
        this.hiddenField.name = this.name;
        this.element.appendChild(this.hiddenField);
        this.setFiles(this.value);
        this.resumable = new Resumable(resumableConfig);
        if (this.resumable.support) {
            this.browse.style.cursor = 'pointer';
            // this.logger.debug('Resume js supported', this.browse);
            this.resumable.assignBrowse(this.browse, false);
            this.resumable.assignDrop(this.drop);
            this.drop.addEventListener('dragover', this.onDragEnter);
            this.drop.addEventListener('dragenter', this.onDragEnter);
            this.drop.addEventListener('dragleave', this.onDragLeave);
            this.drop.addEventListener('drop', this.onDragLeave);
            // this.resumable.assignDrop(this.dropTarget);
            this.resumable.on('fileAdded', this.onFileAdded);
            this.resumable.on('fileSuccess', this.onFileSuccess);
            this.resumable.on('fileProgress', this.onFileProgress);
            this.resumable.on('filesAdded', this.onFilesAdded);
            this.resumable.on('fileRetry', this.onFileRetry);
            this.resumable.on('fileError', this.onFileError);
            this.resumable.on('uploadStart', this.onUploadStart);
            this.resumable.on('complete', this.onComplete);
            this.resumable.on('progress', this.onProgress);
            this.resumable.on('error', this.onError);
            this.resumable.on('pause', this.onPause);
            this.resumable.on('beforeCancel', this.onBeforeCancel);
            this.resumable.on('cancel', this.onCancel);
            this.resumable.on('chunkingStart', this.onChunkingStart);
            this.resumable.on('chunkingProgress', this.onChunkingProgress);
            this.resumable.on('chunkingComplete', this.onChunkingComplete);
        }
        this.logger.debug('Attached');
    }
    private setFiles(value:string)
    {
        let files = value.split(/\s*,\s*/);
        this.handledFiles = files.filter((value:string, index:number) => {
            return value.trim() !== '';
        }).map((value:string, index:number) => {

            return {
                name: value,
                shortname: value.split(/.*[\/|\\]/).pop(),
                previewUrl: this.generatePreviewUrl(value),
                deleteUrl: this.generateDeleteUrl(value)
            }
        });
        const fileValues = this.getFilesValue();
        this.hiddenField.value = fileValues;

    }
    private setFile(name:string, file:Resumable.ResumableFile|null = null)
    {
        this.handledFiles.forEach((handledFile:UploadedFile, index:number) => {
            if (handledFile.file && handledFile.file !== null) {
                this.resumable.removeFile(handledFile.file);
            }
          //  this.importService.delete(handledFile.deleteUrl);
        });
        this.handledFiles = [
            {
                name: name,
                shortname: name.split(/.*[\/|\\]/).pop(),
                previewUrl: this.generatePreviewUrl(name),
                deleteUrl: this.generateDeleteUrl(name),
                file: file
            }
        ];
        const newValue = this.getFilesValue();
        this.hiddenField.value = newValue;
    }

    protected onDragEnter = (evt:DragEvent) => {
        evt.preventDefault();
        let el = <HTMLElement>evt.currentTarget;
        let dt = evt.dataTransfer;
        if (dt && dt.types.indexOf('Files') >= 0) {
            evt.stopPropagation();
            dt.dropEffect = 'copy';
            dt.effectAllowed = 'copy';
            el.classList.add('border-green-700');
        } else if (dt) {
            dt.dropEffect = 'none';
            dt.effectAllowed = 'none';
        }
    };
    protected onDragLeave = (evt:Event) => {
        let el = <HTMLElement>evt.currentTarget;
        el.classList.remove('border-green-700');
    };
    protected onFileAdded = (file:Resumable.ResumableFile, event:DragEvent) => {
        this.logger.debug('onFileAdded', file, event);

        /*
        const message:IImportMessage = {
            name:this.name,
        };
        this.ea.publish(ImportChannels.FILE_ADDED, message);
         */
        this.resumable.upload();
    };
    // File upload completed
    protected onFileSuccess = (file:Resumable.ResumableFile, serverMessage:string) => {
        if (this.multiple === false) {
            this.setFile('@webapp/runtime/uploads/' + file.fileName, file);
        } else {
            this.appendFile('@webapp/runtime/uploads/' + file.fileName, file);
        }
        this.logger.debug('onFileSuccess', file, file);
    };
    // File upload progress
    protected onFileProgress = (file:Resumable.ResumableFile, serverMessage:string) => {
        this.logger.debug('onFileProgress', file, serverMessage);
    };
    protected onFilesAdded = (filesAdded:Resumable.ResumableFile[], filesSkipped:Resumable.ResumableFile[]) => {
        this.logger.debug('onFilesAdded', filesAdded, filesSkipped);
        /*
        const loaderMessage: ILoaderEvent = {
            action: LoaderAction.OPEN
        };
        this.ea.publish(LoaderChannels.MAIN, loaderMessage);

         */
    };
    protected onFileRetry = (file:Resumable.ResumableFile) => {
        this.logger.debug('onFileRetry', file);
    };
    protected onFileError = (file:Resumable.ResumableFile, serverMessage:string) => {
        this.logger.debug('onFileError', file, serverMessage);
    };
    protected onUploadStart = () => {
        this.logger.debug('onUploadStart');
    };
    protected onComplete = () => {
        this.logger.debug('onComplete');
        /*
        const loaderMessage: ILoaderEvent = {
            action: LoaderAction.CLOSE
        };
        this.ea.publish(LoaderChannels.MAIN, loaderMessage);

         */
    };
    protected onProgress = () => {
        this.logger.debug('onProgress');
    };
    protected onError = (serverMessage:string, file:Resumable.ResumableFile) => {
        this.logger.debug('onError', file, serverMessage);
        /*
        const loaderMessage: ILoaderEvent = {
            action: LoaderAction.CLOSE
        };
        this.ea.publish(LoaderChannels.MAIN, loaderMessage);

         */
    };
    protected onPause = () => {
        this.logger.debug('onPause');
    };
    protected onBeforeCancel = () => {
        this.logger.debug('onBeforeCancel');
    };
    protected onCancel = () => {
        this.logger.debug('onCancel');
        /*
        const loaderMessage: ILoaderEvent = {
            action: LoaderAction.CLOSE
        };
        this.ea.publish(LoaderChannels.MAIN, loaderMessage);

         */
    };
    protected onChunkingStart = (file:Resumable.ResumableFile) => {
        this.logger.debug('onChunkingStart', file);
    };
    protected onChunkingProgress = (file:Resumable.ResumableFile, ratio:number) => {
        this.logger.debug('onChunkingProgressd', file, ratio);
    };
    protected onChunkingComplete = (file:Resumable.ResumableFile) => {
        this.logger.debug('onChunkingComplete', file);
    };
}

