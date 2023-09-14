<?php
define('DB_PATH', '../database/tasks.txt');

if (
    $_SERVER['REQUEST_METHOD'] === 'DELETE'
    || (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE')
) {

    $id = intval($_POST['task']['id']);

    $lines = file(DB_PATH, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $key => $line) {
        if ($key === $id) unset($lines[$key]);
    }

    $data = implode(PHP_EOL, $lines);
    file_put_contents(DB_PATH, $data . PHP_EOL);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = trim($_POST['task']['name']);

    if (empty($taskName)) {
        $errors = ['task' => ['name' => 'Não pode ser vazio.']];
    } else {
        file_put_contents(DB_PATH, $taskName . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
} else {
    $tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/json') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($tasks);
            return;
        }
    }
}

$tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);
?>

<!Doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    .form-group {
        margin-bottom: 5px;
    }

    .invalid-feedback {
        color: red;
        display: block;
    }

    .is-invalid {
        border: 1px solid red;
    }

    .form-inline {
        display: inline-block;
        margin-right: 10px;
    }

    .display-inline {
        display: flex;
        align-items: baseline;
        gap: 3px;
    }

    ul li {
        padding: 5px;
    }
</style>

<body>
    <h1>Lista de Tarefas</h1>

    <ul>
        <?php foreach ($tasks as $index => $task) : ?>
            <li>
                <form action="tasks.php" method="POST" class="form-inline">
                    <input type='hidden' name="_method" value='DELETE'>
                    <input type="hidden" name="task[id]" value="<?= $index ?>">
                    <input type="submit" value="Remover">
                </form>
                <span><?= $task ?></span>
            </li>
        <?php endforeach ?>
    </ul>

    <form action="tasks.php" method="POST" class="display-inline">

        <div class="form-group">
            <label for="task_name">Tarefa:</label>
            <input id="task_name" type="text" name="task[name]" class="<?= isset($errors['task']['name']) ? 'is-invalid' : '' ?>">
            <span class="invalid-feedback"><?= isset($errors['task']['name']) ? $errors['task']['name'] : '' ?></span>
        </div>

        <input type="submit" value="Adicionar">
    </form>
</body>

</html>