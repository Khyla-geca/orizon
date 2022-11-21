<?php

class Flight
{
    protected $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function selectAll()
    {
        $sql = "
        SELECT
            flights.id,
            c1.name as departure,
            c2.name as arrival,
            flights.availableSeats
        FROM flights
        LEFT JOIN cities c1 ON c1.id = flights.departure
        LEFT JOIN cities c2 ON c2.id = flights.arrival
        ORDER BY flights.availableSeats ASC
        ;";
        $st = $this->pdo->getConnection()->prepare($sql);
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

    function selectByCity(array $data)
    {
        $sql = "
        SELECT
            flights.id,
            c1.name as departure,
            c2.name as arrival,
            flights.availableSeats
        FROM flights
        LEFT JOIN cities c1 ON c1.id = flights.departure
        LEFT JOIN cities c2 ON c2.id = flights.arrival
        WHERE c1.name = :name OR c2.name = :name
        ;";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('name' => $data['name']);
        if ($st->execute($params)) {
            $rows = array();
            while (($row = $st->fetch(PDO::FETCH_ASSOC)) !== false) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return false;
        }
    }

    function selectBySeats(array $data)
    {
        $sql = "
        SELECT
            flights.id,
            c1.name as departure,
            c2.name as arrival,
            flights.availableSeats
        FROM flights
        LEFT JOIN cities c1 ON c1.id = flights.departure
        LEFT JOIN cities c2 ON c2.id = flights.arrival
        HAVING flights.availableSeats >= :seats
        ;";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('seats' => $data['availableSeats']);
        if ($st->execute($params)) {
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
        $sql = "
            INSERT INTO flights (
                departure, 
                arrival, 
                availableSeats) 
            VALUES (
                :departure, 
                :arrival, 
                :availableSeats
            )";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array(
            'departure' => $data['departure'],
            'arrival' => $data['arrival'],
            'availableSeats' => $data['availableSeats']
        );
        if ($st->execute($params)) {
            return true;
        }
    }

    function update(array $data)
    {
        $sql = "
            UPDATE flights 
            SET availableSeats = :seats 
            WHERE id = :id";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('id' => $data['id'], 'seats' => $data['availableSeats']);
        if ($st->execute($params)) {
            return true;
        }
    }

    function delete(array $data)
    {
        $sql = "DELETE FROM flights WHERE id = :id";
        $st = $this->pdo->getConnection()->prepare($sql);
        $params = array('id' => $data['id']);
        if ($st->execute($params)) {
            return true;
        }
    }
}