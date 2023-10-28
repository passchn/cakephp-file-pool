import { ref, Ref } from "vue";
import FilePool from "./FilePool";
import ServerFile from "./ServerFile";

export default function useServerFiles(filePool: FilePool): Ref<ServerFile[]> {

    const files = ref<ServerFile[]>();

    filePool.findAll();
    filePool.onUpdate((filesMap) => {
        files.value = [];
        filesMap.forEach(file => {
            files.value.push(file);
        });
    });

    return files;
}
