<?php

class City
{
    protected $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function selectAll()
    {
        $sql = "SELECT cities.id, cities.name FROM cities;";
        $st = $this->pdo->getConnection()->prepare($sql);
        $st->execute();
        if ($st->execute()) {
            $rows = array();
            while (($row = $st->fetch(PDO::FETCH_ASSOC)) !== false) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return false;
        }
    }

    function create(array $data)
    {
        $sql = "INSERT INTO cities (name) VALUES (:name);";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('name' => $data['name']);
        if ($st->execute($params)) {
            return true;
        }
    }

    function update(array $data)
    {
        $sql = "UPDATE cities SET name = :name WHERE id = :id;";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('id' => $data['id'], 'name' => $data['name']);
        if ($st->execute($params)) {
            return true;
        }
    }

    function delete(array $data)
    {
        $sql = "DELETE FROM cities WHERE id = :id;";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('id' => $data['id']);
        if ($st->execute($params)) {
            return true;
        }
    }
}