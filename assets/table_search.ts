export default class Table {
    Columns: Array<string>;
    Data: Array<Array<string>>;
    table: HTMLElement;
    search: HTMLInputElement;

    constructor(table: HTMLElement, search: HTMLInputElement = null) {
        this.Columns = new Array<string>();
        this.Data = new Array<Array<string>>();
        this.table = table;
        this.search = search;
        this.extract();
        this.addEventHandler();
    }

    private extract() {
        //extract header
        let headers: NodeListOf<Element> = this.table.querySelectorAll("thead > tr > th");
        headers.forEach((elt: Element) => {
            this.Columns.push(elt.textContent);
        });
        //extract body
        let body: NodeListOf<Element> = this.table.querySelectorAll("tbody > tr");
        body.forEach((elt: Element) => {
            let tmpArr: Array<string> = new Array<string>();

            let children: NodeListOf<Element> = elt.querySelectorAll("td");
            children.forEach((child: Element) => {
                tmpArr.push(child.innerHTML);
            })
            this.Data.push(tmpArr);
        })
    }

    public render() {
        let localData: Array<Array<string>> = this.Data;

        console.log(this.search.textContent)
        if (this.search != null) {
            localData = new Array<Array<string>>();
            this.Data.forEach((elt: Array<string>) => {

                let flag: boolean = false;
                elt.forEach((str: string) => {
                    if (str.includes(this.search.value)) {
                        flag = true;
                    }
                })
                if (flag)
                    localData.push(elt);
            });
        }

        let html: Element;
        html = document.createElement("tbody");

        localData.forEach((data: Array<string>) => {
            let tmp: Element = document.createElement("tr");

            data.forEach((elt: string) => {
                let td: HTMLElement = document.createElement("td");
                td.innerHTML = elt;
                tmp.appendChild(td);
            })
            html.append(tmp);
        });

        this.table.removeChild(this.table.querySelector("tbody"));
        this.table.appendChild(html);


    }

    private addEventHandler() {
        this.search.addEventListener("change", () => {
            this.render()
        });
    }

}
