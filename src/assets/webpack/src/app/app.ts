import {ILogger, resolve} from 'aurelia';
export class FractalCmsApp {
    constructor(
        private readonly logger: ILogger = resolve(ILogger),
    ) {
        this.logger = logger.scopeTo('FractalCmsApp');

    }

    public binding() {
        this.logger.trace('binding');
    }

    public attaching()
    {
        this.logger.trace('Attaching');
    }
}