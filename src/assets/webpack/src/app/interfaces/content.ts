/** Interface modal**/

export interface IConfigItem {
    id:number;
    name:string;
}
export interface IItem {
    parentId:number;
    order?:number;
    configItemId?:number;
    data?:string;
}