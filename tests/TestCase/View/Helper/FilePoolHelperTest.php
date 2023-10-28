<?php
declare(strict_types=1);

namespace FilePool\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use FilePool\View\Helper\FilePoolHelper;

/**
 * FilePool\View\Helper\FilePoolHelper Test Case
 */
class FilePoolHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \FilePool\View\Helper\FilePoolHelper
     */
    protected $FilePool;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->FilePool = new FilePoolHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FilePool);

        parent::tearDown();
    }
}
