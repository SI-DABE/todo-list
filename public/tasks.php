<?php
    require '../app/models/Task.php';

    $method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            echo 'GET';
            $task = new Task();
            break;
        case 'POST':
            echo 'POST';
            $task = new Task(name: $_POST['task']['name']);
            if ($task->save())
                echo 'Tarefa adicionada com sucesso!';
            else
                echo 'Não foi possível adicionar a tarefa!';
            break;
        case 'DELETE': 
            echo 'DELETE'; 
            break;
    }

    $tasks = Task::all();
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
        <?php foreach ($tasks as $index => $task_) : ?>
            <li>
                <form action="tasks.php" method="POST" class="form-inline">
                    <input type='hidden' name="_method" value='DELETE'>
                    <input type="hidden" name="task[id]" value="<?= $index ?>">
                    <input type="submit" value="Remover">
                </form>
                <span><?= $task_->getName(); ?></span>
            </li>
        <?php endforeach ?>
    </ul>

    <form action="tasks.php" method="POST" class="display-inline">

        <div class="form-group">
            <label for="task_name">Tarefa:</label>
            <input id="task_name" type="text" name="task[name]" class="<?= $task->errors('name') ? 'is-invalid' : '' ?>">
            <span class="invalid-feedback"><?= $task->errors('name') ?></span>
        </div>

        <input type="submit" value="Adicionar">
    </form>
</body>

</html>