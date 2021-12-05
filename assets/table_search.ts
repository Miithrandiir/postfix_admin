export default class Table {
    Columns: Array<String>;
    Data: Array<Array<String>>;

    constructor(col: Array<String>, data: Array<Array<String>>) {
        this.Columns = col;
        this.Data = data;
    }

    public generate_form() : HTMLElement {

        let table = document.createElement('table');
        table.classList.add('table');
        return table;
    }

    public render(elt: HTMLElement) {
        elt.appendChild(this.generate_form());
    }
}
