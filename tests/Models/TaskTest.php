<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    public function testCanSetTaskName(): void
    {
        $taskName = 'Estudar composer';

        $task = new Task($taskName);

        $this->assertSame($taskName, $task->getName());
    }
}
