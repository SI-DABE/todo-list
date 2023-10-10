<?php

namespace Tests\Router;

use App\Controllers\BaseController;
use PHPUnit\Framework\Assert;

class MockController extends BaseController
{
    public function itMustBeCalled()
    {
        Assert::assertTrue(true);
    }
    public function itDontMustBeCalled()
    {
        Assert::assertTrue(false);
    }
}
