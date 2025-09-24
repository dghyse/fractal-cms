import { HttpClientConfiguration, IHttpClient } from '@aurelia/fetch-client';
import {ILogger, resolve} from 'aurelia';
import {EApi} from "../enums/api";

export class ApiServices {

    public constructor(
        private readonly httpClient: IHttpClient = resolve(IHttpClient),
        private readonly logger: ILogger = resolve(ILogger).scopeTo('ApiServices')
    )
    {
        this.logger.trace('constructor');
        this.httpClient.configure((config: HttpClientConfiguration) => {
            config.withDefaults({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'include'
            }).withInterceptor({
                request(request) {
                    console.log(`Requesting ${request.method} ${request.url}`);
                    return request;
                },
                response(response) {
                    console.log(`Received ${response.status} ${response.url}`);
                    return response;
                },
            }).rejectErrorResponses();
            return config;
        });

    }


    /**
     *
     * @param url
     * @param body
     */
    public postForm(url:string, body:FormData): Promise<string>
    {
        return this.httpClient.fetch(url, {
            method: 'POST',
            body:body
        })
            .then((response:Response) => {
                return response.text();
            });
    }


    public delete(url:string): Promise<string>
    {
        return this.httpClient.fetch(url, {
            method: 'DELETE',
        })
            .then((response:Response) => {
                return response.text();
            });
    }

    public manageItems(id:number, body:FormData): Promise<string>
    {
        const url:string = EApi.ITEM_MANAGE.replace('{contentId}', id.toString());
        return this.httpClient.fetch(url, {
            method: 'POST',
            body:body,
            headers : {
                Accept: 'text/html'
            }
        })
            .then((response:Response) => {
                return response.text();
            });
    }
}
