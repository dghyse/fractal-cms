const displayText:any = {
    upItem:'Un item a été remonter',
    downItem:'Un item a été descendu',
    deleteItem:'Un item a été supprimé'
};


export const getTextAlert = (name:string) => {
    if (name) {
        if (displayText.hasOwnProperty(name)) {
            name = displayText[name];
        } else {
            name = displayText(name);
        }
    }
    return name;
}