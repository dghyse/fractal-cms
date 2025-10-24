import {EMenuItemAction} from "../enums/menu";

export interface IMenuItem {
    sourceMenuItemId:number;
    destMenuItemId:number;
}
export interface IMoveMenuItem {
    name:string;
    action:EMenuItemAction
}