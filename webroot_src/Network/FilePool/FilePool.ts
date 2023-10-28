import HttpRequest from "../HttpRequest/HttpRequest";
import {AxiosError, AxiosResponse} from "axios";
import ServerFile from "./ServerFile";

export default class FilePool {
    protected ownerSource: string;
    protected ownerId: string;
    protected files: Map<string, ServerFile>;
    protected onUpdateCallbacks: Set<(files: Map<string, ServerFile>) => void>;

    constructor(source: string, id: string) {
        this.ownerSource = source;
        this.ownerId = id;
        this.files = new Map();
        this.onUpdateCallbacks = new Set();
    }

    public add(file: File) {
        const tempId = file.name + file.type;
        this.files.set(tempId, new ServerFile({
            id: tempId,
            asset: {
                filename: file.name,
            },
        }, {
            isUploaded: false,
        }));
        this.update();
        this.handleRequest('add', {
            file: file,
        }, (response) => {
            this.files.delete(tempId);
            this.files.set(response.data.id, new ServerFile(response.data, {
                isUploaded: true,
            }));
            this.update();
        }, (error) => {
            const errorFile = this.files.get(tempId);
            if (errorFile !== undefined) {
                errorFile.setError(error.message);
            }
            this.update();
        });
    }

    public remove(fileId: string) {
        const file = this.files.get(fileId);
        file?.startDeletionTimeout(3500).then(isDeleted => {
            if (!isDeleted) {
                return;
            }
            this.handleRequest('delete', {
                id: fileId,
            }, (response) => {
                this.files.delete(fileId);
                this.update();
            }, (error) => {
                console.error(error);
            });
            this.update();
        });

        this.update();
    }

    public edit(fileId: string, data: FormData) {
        return new Promise(resolve => {
            this.handleRequest('edit', {
                fileId: fileId,
                title: data.get('title'),
                description: data.get('description'),
                category: data.get('category'),
            }, successResponse => {
                this.findAll();
                resolve(true);
            }, errorResponse => {
                console.error(errorResponse);
                resolve(false);
            });
        });
    }

    public findAll() {
        this.handleRequest('index', {},
            (response) => {
                this.files.forEach(file => {
                    if (file.isUploaded) {
                        this.files.delete(file.id);
                    }
                });
                response.data.forEach((file: any) => {
                    this.files.set(file.id, new ServerFile(file, {
                        isUploaded: true,
                    }));
                });
                this.update();
            },
            (error) => {
                console.error(error);
            });
    }

    public onUpdate(callback: (files: Map<string, ServerFile>) => void) {
        this.onUpdateCallbacks.add(callback);
    }

    protected update() {
        this.onUpdateCallbacks.forEach(callback => {
            callback(this.files);
        });
    }

    protected handleRequest(
        action: string,
        fields: any,
        onSuccess: (response: AxiosResponse) => void,
        onError: (error: AxiosError) => void
    ) {
        const formData = new FormData();

        Object.keys(fields).forEach((key) => {
            formData.set(key, fields[key]);
        });

        formData.set('ownerModel', this.ownerSource);
        formData.set('ownerId', this.ownerId);

        HttpRequest.post(`/file-pool/file-pool-assets/${action}.json`, formData)
            .then(onSuccess)
            .catch(onError);
    }
}
