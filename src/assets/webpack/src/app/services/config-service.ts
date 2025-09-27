export class ConfigService {
    private apiBaseUrl:string;

    constructor() {

    }

    /**
     * get api base url
     */
    public getApiBaseUrl()
    {
        if (!this.apiBaseUrl) {
            this.apiBaseUrl = ((window as any).apiBaseUrl) ? (window as any).apiBaseUrl : 'cms';
        }
        return this.apiBaseUrl;
    }
}