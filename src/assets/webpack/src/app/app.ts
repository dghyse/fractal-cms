import {ILogger, resolve} from 'aurelia';
export class CmsApp {

   /* static routes = [
        {
            path: '',
            redirectTo: 'accueil'
        },
        {
            path: 'cms',
            component: () => import('./pages/accueil'),
            id: 'cms',
        },
    ];*/

    constructor(
        private readonly logger: ILogger = resolve(ILogger),
    ) {
        this.logger = logger.scopeTo('CmsApp');

    }

    public binding() {
        this.logger.trace('binding');
    }

    public attaching()
    {
        this.logger.trace('Attaching');
    }
}