<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\Entity $entity
 * @var string|null $title
 * @var bool $allowDelete
 * @var bool $allowEdit
 * @var bool $allowUpload
 * @var array $translations
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
    >
        <script data-translations type="application/json"><?= json_encode($translations) ?></script>
    </div>
</fieldset>

<?= $this->ViteScripts->script([], new ViteHelperConfig([
    'forceProductionMode' => 0,
    'plugin' => 'FilePool',
    'development' => [
        'scriptEntries' => [
            \Cake\Core\Plugin::path('FilePool') . 'webroot_src/main.ts',
        ],
        // url of the vite dev server
        'url' => 'http://localhost:3001',
    ]
])) ?>
