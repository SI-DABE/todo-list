<?php

namespace App\Lib;

use App\Models\User;
use Core\Db\Database;
use PDO;

class Paginator
{
    private int $totalOfRegisters = 0;
    private int $totalOfPages = 0;
    private int $offset = 0;
    private int $totalOfRegistersOfPage = 0;
    private array $registers = [];

    public function __construct(
        private $class,
        private int $page,
        private int $per_page,
    ) {
        $this->loadTotals();
        $this->loadRegisters();
    }

    public function totalOfRegisters()
    {
        return $this->totalOfRegisters;
    }

    public function totalOfPages()
    {
        return $this->totalOfPages;
    }

    public function registers()
    {
        return $this->registers;
    }

    public function entriesInfo()
    {
        $totalVisualizedBegin = $this->offset + 1;
        $totalVisualizedEnd = $totalVisualizedBegin + $this->totalOfRegistersOfPage - 1;
        return "Mostrando {$totalVisualizedBegin} - {$totalVisualizedEnd} de {$this->totalOfRegisters}";
    }

    private function loadTotals()
    {
        $table = $this->class::getTable();
        $sql = "SELECT COUNT(*) FROM {$table}";
        $pdo = Database::getDBConnection();
        $this->totalOfRegisters = $pdo->query($sql)->fetchColumn();

        $this->totalOfPages = ceil($this->totalOfRegisters / $this->per_page);
        $this->offset = $this->per_page * ($this->page - 1);
    }

    private function loadRegisters()
    {
        $this->registers = [];

        $table = $this->class::getTable();
        $attributes = implode(', ', $this->class::getAttributes());
        $sql = <<<SQL
            SELECT id, {$attributes} FROM {$table} LIMIT :limit OFFSET :offset;
        SQL;

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue('limit', $this->per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $this->offset, PDO::PARAM_INT);

        $stmt->execute();
        $resp = $stmt->fetchAll(PDO::FETCH_NUM);
        $this->totalOfRegistersOfPage = $stmt->rowCount();

        foreach ($resp as $row) {
            $this->registers[] = new $this->class(...$row);
        }
    }
}
