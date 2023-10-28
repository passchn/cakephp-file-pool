<?php
declare(strict_types=1);

namespace FilePool\View\Helper;

use Cake\Datasource\EntityInterface;
use Cake\View\Helper;

/**
 * FilePool helper
 */
class FilePoolHelper extends Helper
{
    public function forEntity(
        EntityInterface $entity,
        ?string $title = null,
        bool $allowDelete = false,
        bool $allowEdit = false,
        bool $allowUpload = false,
    ) {
        return $this->getView()->element('FilePool.helper/FilePool', [
            'entity' => $entity,
            'allowDelete' => $allowDelete,
            'allowEdit' => $allowEdit,
            'allowUpload' => $allowUpload,
            'title' => $title,
            'translations' => [
                'dragAndDropText' => __d('FilePool', 'Drop file to upload'),
                'editFile' => __d('FilePool', 'Edit file'),
                'deleteFile' => __d('FilePool', 'Delete file'),
                'cancelDeletion' => __d('FilePool', 'Cancel deletion'),
                'addFile' => __d('FilePool', 'Add file'),
                'noFiles' => __d('FilePool', 'No files'),
            ],
        ]);
    }
}
