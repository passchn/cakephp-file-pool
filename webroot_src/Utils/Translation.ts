export default class Translation
{
    private data: Object;
    constructor(data: Object) {
        this.data = data;
    }

    public get(key: string): string
    {
        if (this.data.hasOwnProperty(key)) {
            return this.data[key];
        }

        return key;
    }
}
