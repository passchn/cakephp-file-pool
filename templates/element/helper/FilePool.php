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

use Cake\Core\Configure;

if (!$this->helpers()->has('ViteScripts')) {
    $this->loadHelper('ViteHelper.ViteScripts');
}
$this->ViteScripts->pluginScript('FilePool', options: [
    'block' => Configure::read('FilePool.ViewBlock', 'script'),
]);
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
