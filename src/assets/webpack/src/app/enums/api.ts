export enum EApi {
    ITEM_MANAGE = '/cms/contents/{contentId}/manage-items',
    IMPORT_ASYNC_UPLOAD = '/cms/api/file/upload',
    IMPORT_ASYNC_PREVIEW = '/cms/api/file/preview?name=__name__',
    IMPORT_ASYNC_DELETE = '/cms/api/file/delete?name=__name__',
}