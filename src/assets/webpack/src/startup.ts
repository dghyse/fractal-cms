//Aurelia imports
import Aurelia, { ConsoleSink, LoggerConfiguration, LogLevel } from 'aurelia';
import {ValidationHtmlConfiguration, ValidationTrigger} from "@aurelia/validation-html";
import {StateDefaultConfiguration} from '@aurelia/state';
// Plugins imports
// app imports
import * as globalAttributes from './app/attributes/index';
import * as globalComponents from './app/components/index';
import {CmsApp} from "./app/app";

declare const webpackBaseUrl: string;
declare let __webpack_public_path__: string;
declare let apiBaseUrl: string;
if (webpackBaseUrl !== undefined) {
    __webpack_public_path__ = webpackBaseUrl;
}
declare const PRODUCTION:boolean;


const page = document.querySelector('body') as HTMLElement;
const au = Aurelia
     .register(globalAttributes)
    .register(globalComponents)
    .register(ValidationHtmlConfiguration.customize((options) => {
        // customization callback
        options.DefaultTrigger = ValidationTrigger.blur;
    }));

if(PRODUCTION == false) {
    au.register(LoggerConfiguration.create({
        level: LogLevel.trace,
        colorOptions: 'colors',
        sinks: [ConsoleSink]
    }));

}
au.enhance({
    host: page,
    component: CmsApp
});
