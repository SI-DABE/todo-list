<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Core\Db\Database;
use PDO;

class TaskUsersService
{

    public function __construct(
        private Task $task
    ) {
    }

    public function all(): array
    {
        $users = [];
        $sql = <<<SQL
            SELECT users.id, users.name, users.email
            FROM  users, tasks_users
            WHERE users.id = tasks_users.user_id AND
                  tasks_users.task_id = :task_id;
        SQL;

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('task_id', $this->task->getId());

        $stmt->execute();
        $resp = $stmt->fetchAll(PDO::FETCH_NUM);

        foreach ($resp as $row) {
            $users[] = new User(...$row);
        }


        return $users;
    }

    public function new($user_id): TaskUser
    {
        return new TaskUser(
            task_id: $this->task->getId(),
            user_id: $user_id
        );
    }

    public function findByUserId($user_id): TaskUser | null
    {
        return TaskUser::findBy(
            [
                'task_id' => $this->task->getId(),
                'user_id' => $user_id
            ]
        );
    }
}
