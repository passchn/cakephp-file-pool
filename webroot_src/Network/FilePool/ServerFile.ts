export default class ServerFile
{

    protected data: any;
    protected info: ServerFileInfo;
    protected error: string|null = null;
    protected _isDeleted: Boolean;
    protected deletionTimeouts = new Set<number>();

    constructor(data: object, info: ServerFileInfo = {}) {
        this.data = data;
        this.info = info;
        this._isDeleted = false;
    }

    public get id(): string
    {
        return this.data.id;
    }

    public get ownerId(): string
    {
        return this.data.owner_id;
    }

    public get ownerSource(): string
    {
        return this.data.owner_source;
    }

    public get filename(): string
    {
        return this.data.asset.filename;
    }

    public get title(): string
    {
        return this.data.asset.title || this.data.asset.filename;
    }

    public get editLink(): string
    {
        return this.data.edit_link || '';
    }

    public get isUploaded(): boolean
    {
        return this.info.isUploaded || false;
    }

    public get size(): string
    {
        return this.data.nice_filesize;
    }

    public get type(): string
    {
        return this.data.asset.mimetype;
    }

    public get downloadLink(): string
    {
        return this.data.download_link;
    }

    public get isDeleted(): Boolean
    {
        return this._isDeleted && !this.info.isUploaded;
    }

    public getError(): string|null
    {
        return this.error;
    }

    public setError(error: string) {
        this.error = error;
    }

    public startDeletionTimeout(timeout: number): Promise<Boolean>
    {
        const wasUploaded = this.info.isUploaded;
        this._isDeleted = true;
        this.info.isUploaded = false;

        return new Promise(resolve => {
            const timeoutId = setTimeout(() => {
                resolve(this._isDeleted);
                if (!this._isDeleted) {
                    this.info.isUploaded = wasUploaded;
                }
            }, timeout);

            this.deletionTimeouts.add(timeoutId);
        })
    }

    public restore() {
        this._isDeleted = false;
        this.info.isUploaded = true;
        this.deletionTimeouts.forEach(timeoutId => {
            clearTimeout(timeoutId);
        })
        this.deletionTimeouts.clear();
    }
}

type ServerFileInfo = {
    isUploaded?: boolean,
};
