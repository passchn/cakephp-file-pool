<?php
declare(strict_types=1);

namespace FilePool\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FilePoolAssetsFixture
 */
class FilePoolAssetsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => '9e723170-3475-4363-b423-bbca778df117',
                'owner_source' => 'Lorem ipsum dolor sit amet',
                'owner_id' => '61b25fe9-df83-4153-b800-2d73be5496b5',
                'asset_id' => '0d7c4169-0b65-4ba6-96fd-58e697a61541',
                'sort' => 1,
                'created' => 1698489724,
                'modified' => 1698489724,
            ],
        ];
        parent::init();
    }
}
