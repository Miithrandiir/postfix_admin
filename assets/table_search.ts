export default class Table {
    Columns: Array<string>;
    Data: Array<Array<string>>;
    table: HTMLElement;
    search: HTMLInputElement;

    constructor(table: HTMLElement) {
        this.Columns = new Array<string>();
        this.Data = new Array<Array<string>>();
        this.table = table;
        this.extract();
        const form: HTMLFormElement = this.render_search_form();
        this.table.parentElement.insertBefore(form, this.table);
    }

    public render() {
        let localData: Array<Array<string>> = this.Data;

        if (this.search != null) {
            localData = new Array<Array<string>>();
            this.Data.forEach((elt: Array<string>) => {

                let flag = false;
                elt.forEach((str: string) => {
                    if (str.includes(this.search.value)) {
                        flag = true;
                    }
                })
                if (flag)
                    localData.push(elt);
            });
        }

        const html = document.createElement("tbody");

        localData.forEach((data: Array<string>) => {
            const tmp: Element = document.createElement("tr");

            data.forEach((elt: string) => {
                const td: HTMLElement = document.createElement("td");
                td.innerHTML = elt;
                tmp.appendChild(td);
            })
            html.append(tmp);
        });

        if (localData.length === 0) {
            const tmp: Element = document.createElement("tr");
            const td: HTMLTableCellElement = document.createElement("td");
            td.textContent = "No data available.";
            td.colSpan = this.Columns.length;
            tmp.appendChild(td);
            html.append(tmp);
        }

        this.table.removeChild(this.table.querySelector("tbody"));
        this.table.appendChild(html);
    }

    private extract() {
        //extract header
        const headers: NodeListOf<Element> = this.table.querySelectorAll("thead > tr > th");
        headers.forEach((elt: Element) => {
            this.Columns.push(elt.textContent);
        });
        //extract body
        const body: NodeListOf<Element> = this.table.querySelectorAll("tbody > tr");
        body.forEach((elt: Element) => {
            const tmpArr: Array<string> = new Array<string>();

            const children: NodeListOf<Element> = elt.querySelectorAll("td");
            children.forEach((child: Element) => {
                tmpArr.push(child.innerHTML);
            })
            this.Data.push(tmpArr);
        })
    }

    private render_search_form(): HTMLFormElement {
        const form: HTMLFormElement = document.createElement('form');
        form.onsubmit = (e: Event) => {
            e.preventDefault();
            return false;
        }
        form.classList.add('form');
        form.classList.add('table-search');

        const div: HTMLDivElement = document.createElement("div");
        div.classList.add('input-group');

        const input: HTMLInputElement = document.createElement('input');
        input.classList.add('input');
        input.ariaLabel = "search";
        input.placeholder = "search";

        input.onkeyup = () => {
            this.render();
        }
        this.search = input;
        div.appendChild(input);
        form.appendChild(div);
        return form;
    }
}
