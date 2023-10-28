<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\Entity $entity
 * @var string|null $title
 * @var bool $allowDelete
 * @var bool $allowEdit
 * @var bool $allowUpload
 */

use ViteHelper\Utilities\ViteHelperConfig;

?>

<fieldset class="my-6">
    <?php if ($title !== null) : ?>
        <legend class="text-2xl font-bold mb-2">
            <?= $title ?>
        </legend>
    <?php endif; ?>
    <div
        data-file-pool
        data-owner-id="<?= $entity->id ?>"
        data-owner-source="<?= $entity->getSource() ?>"
        data-allow-delete="<?= $allowDelete ?>"
        data-allow-edit="<?= $allowEdit ?>"
        data-allow-upload="<?= $allowUpload ?>"
    ></div>
</fieldset>

<?= $this->ViteScripts->script([], new ViteHelperConfig([
    'forceProductionMode' => 1,
    'plugin' => 'FilePool',
])) ?>
