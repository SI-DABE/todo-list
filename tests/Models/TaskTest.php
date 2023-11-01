<?php

namespace Tests\Models;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testShouldSaveTask(): void
    {
        $taskName = 'Estudar composer';
        $task = new Task(name: $taskName);

        $this->assertTrue($task->save());
        $this->assertNotEquals(-1, $task->getId());
        $this->assertEquals(1, sizeof(Task::all()));
    }

    public function testShouldReturnAllTheTasks(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $task = new Task(name: 'Task ' . $i);
            $task->save();
        }
        $this->assertEquals(5, sizeof(Task::all()));
    }
}
