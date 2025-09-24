export enum EApi {
    ITEM_MANAGE = '/contents/{contentId}/manage-items',
    IMPORT_ASYNC_UPLOAD = '/api/file/upload',
    IMPORT_ASYNC_PREVIEW = '/api/file/preview?name=__name__',
    IMPORT_ASYNC_DELETE = '/api/file/delete?name=__name__',
}