<?php
/**
 * @var \Cake\View\View $this
 * @var \Cake\ORM\Entity $entity
 * @var string|null $title
 * @var bool $allowDelete
 * @var bool $allowEdit
 * @var bool $allowUpload
 * @var array $translations
 */

use ViteHelper\Utilities\ViteHelperConfig;


if (!$this->helpers()->has('ViteScripts')) {
    $this->addHelper('ViteHelper.ViteScripts');
}

$this->ViteScripts->script([], new ViteHelperConfig([
    'forceProductionMode' => 1,
    'plugin' => 'FilePool',
    'development' => [
        'scriptEntries' => [
            \Cake\Core\Plugin::path('FilePool') . 'webroot_src/main.ts',
        ],
        // url of the vite dev server
        'url' => 'http://localhost:3001',
    ],
    'viewBlocks' => [
        'script' => \Cake\Core\Configure::read('FilePool.ViewBlock', 'script'),
    ],
]));

?>

<fieldset class="file-pool-wrapper">
    <?php if ($title !== null) : ?>
        <legend>
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
