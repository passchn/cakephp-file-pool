<?php
declare(strict_types=1);

namespace FilePool\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\FrozenDate;
use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * FilePoolAsset Entity
 *
 * @property string $id
 * @property string $owner_source
 * @property string $owner_id
 * @property string $asset_id
 * @property int $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Assets\Model\Entity\Asset $asset
 *
 * @property string $nice_filesize
 * @property string|null $download_link
 * @property string|null $edit_link
 */
class FilePoolAsset extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'owner_source' => true,
        'owner_id' => true,
        'asset_id' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'asset' => true,
    ];

    protected $_virtual = [
        'nice_filesize',
        'download_link',
        'edit_link',
    ];

    protected function _getNiceFilesize(): string
    {
        return $this->asset?->getFileSizeInfo() ?: ' - ';
    }

    protected function _getDownloadLink(): ?string
    {
        return $this->asset?->getDownloadLink();
    }

    protected function _getEditLink(): ?string
    {
        return Router::url([
            'plugin' => 'Assets',
            'prefix' => Configure::read('AssetsPlugin.Routes.adminPrefix', 'Admin'),
            'controller' => 'Assets',
            'action' => 'edit',
            $this->asset_id,
        ]);
    }
}
