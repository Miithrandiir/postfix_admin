export default class Table {
    Columns: Array<string>;
    data: Array<Array<string>>;
    table: HTMLElement;
    search: HTMLInputElement;

    page: number;
    page_limit: number;

    constructor(table: HTMLElement) {
        this.Columns = new Array<string>();
        this.data = new Array<Array<string>>();
        this.table = table;
        this.extract();
        const form: HTMLFormElement = this.render_search_form();
        this.table.parentElement.insertBefore(form, this.table);
        this.page = 1;
        this.page_limit = 10;
        this.render();
    }

    public render() {
        let localData: Array<Array<string>> = this.data;

        if (this.search != null) {
            localData = new Array<Array<string>>();
            this.data.forEach((elt: Array<string>) => {

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

        //Generation of data
        const html = this.pagination(localData);
        this.table.removeChild(this.table.querySelector("tbody"));
        this.table.appendChild(html);

        this.render_pagination_button(localData);
    }

    private pagination(data: Array<Array<string>>): HTMLElement {
        const tbody: HTMLElement = document.createElement("tbody");
        const min = this.page_limit * (this.page-1);
        const max = min + this.page_limit;
        for (let i = min; i < max; i++) {
            const tr: HTMLTableRowElement = document.createElement('tr');
            if(i >= data.length)
                break;
            data[i].forEach((elt) => {
                const td: HTMLTableCellElement = document.createElement('td');
                td.innerHTML = elt;
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        }

        return tbody;
    }

    private render_pagination_button(data: Array<Array<string>>) {

        if (this.table.parentElement.lastElementChild instanceof HTMLDivElement) {
            this.table.parentElement.removeChild(this.table.parentElement.lastElementChild);
        }
        //Button Generation
        const pagination_div: HTMLDivElement = document.createElement('div');
        pagination_div.classList.add('pagination');
        const pagination_first: HTMLButtonElement = document.createElement('button');
        pagination_first.innerText = "<<"
        pagination_first.onclick = () => {
            this.page = 1;
            this.render();
        };
        pagination_div.appendChild(pagination_first);


        if (this.page - 1 > 0) {
            const pagination_previous: HTMLButtonElement = document.createElement('button');
            pagination_previous.innerText = (this.page - 1).toString();
            pagination_previous.onclick = () => {
                this.page--;
                this.render();
            };
            pagination_div.appendChild(pagination_previous);
        }

        const pagination_actual: HTMLButtonElement = document.createElement('button');
        pagination_actual.innerText = (this.page).toString();
        pagination_actual.classList.add('button-disable');
        pagination_actual.disabled = true;
        pagination_div.appendChild(pagination_actual);

        if (this.page + 1 <= Math.ceil(data.length/this.page_limit)) {
            const pagination_next: HTMLButtonElement = document.createElement('button');
            pagination_next.innerText = (this.page + 1).toString();
            pagination_next.onclick = () => {
                this.page++;
                this.render();
            };
            pagination_div.appendChild(pagination_next);
        }

        const pagination_last: HTMLButtonElement = document.createElement('button');
        pagination_last.innerText = ">>"
        pagination_last.onclick = () => {
            //this.page = (this.Data.length)/this.page_limit;
            this.render();
        };
        pagination_div.appendChild(pagination_last);


        this.table.parentElement.appendChild(pagination_div);
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
            this.data.push(tmpArr);
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
            this.page = 1;
            this.render();
        }
        this.search = input;
        div.appendChild(input);
        form.appendChild(div);
        return form;
    }
}
