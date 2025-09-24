import {IEventAggregator, ILogger, IPlatform, IDisposable, watch, resolve} from 'aurelia';
import {IStore} from "@aurelia/state";
import {IRouteableComponent} from "@aurelia/router-direct";

export class Accueil implements IRouteableComponent
{

    constructor(
        private readonly logger: ILogger = resolve(ILogger)
    ) {
        this.logger = logger.scopeTo('Accueil');
        this.logger.trace('constructor');
    }

    public binding() {
        this.logger.trace('binding');

    }

    public bound() {
        this.logger.trace('bound');
    }
    public unbinding()
    {
        this.logger.trace('unbinding');
    }
}