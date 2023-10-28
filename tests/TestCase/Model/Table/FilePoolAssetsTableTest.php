<?php
declare(strict_types=1);

namespace FilePool\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use FilePool\Model\Table\FilePoolAssetsTable;

/**
 * FilePool\Model\Table\FilePoolAssetsTable Test Case
 */
class FilePoolAssetsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \FilePool\Model\Table\FilePoolAssetsTable
     */
    protected $FilePoolAssets;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.FilePool.FilePoolAssets',
        'plugin.FilePool.Assets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FilePoolAssets') ? [] : ['className' => FilePoolAssetsTable::class];
        $this->FilePoolAssets = $this->getTableLocator()->get('FilePoolAssets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FilePoolAssets);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \FilePool\Model\Table\FilePoolAssetsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
