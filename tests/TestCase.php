<?php

namespace Tests;

use Core\Db\Database;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public static function setUpBeforeClass(): void
    {
        Database::create();
    }

    public static function tearDownAfterClass(): void
    {
        Database::drop();
    }

    public function setUp(): void
    {
        Database::createTables();
    }
}
