<?php

class FlightController
{
    public function read()
    {
        $this->setHeaders('GET');

        $req = $this->startDb();
        $flight = $req['flight'];
        $data = $req['data'];

        $recordset = $flight->selectAll();

        if ($recordset !== false) {
            http_response_code(201);
            echo json_encode($recordset);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No flights found."));
        }
    }

    public function filter()
    {
        $this->setHeaders('GET');

        $req = $this->startDb();
        $flight = $req['flight'];
        $data = $req['data'];

        if (array_key_exists('name', $data)) {
            $recordset = $flight->selectByCity($data);
        } else if (array_key_exists('availableSeats', $data)) {
            $recordset = $flight->selectBySeats($data);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Error: Data is missing."));
        }

        if ($recordset !== false) {
            http_response_code(201);
            echo json_encode($recordset);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No city found."));
        }
    }

    public function create()
    {
        $this->setHeaders('POST');

        $req = $this->startDb();
        $flight = $req['flight'];
        $data = $req['data'];

        if (
            !empty($data['departure']) &&
            !empty($data['arrival']) &&
            !empty($data['availableSeats'])
        ) {
            if ($flight->create($data)) {
                http_response_code(201);
                echo json_encode(array("message" => "A new flight has been added."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Flight was not added."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Error: Data is missing."));
        }
    }

    public function update()
    {
        $this->setHeaders('PUT');

        $req = $this->startDb();
        $flight = $req['flight'];
        $data = $req['data'];

        if (
            !empty($data['id']) &&
            !empty($data['availableSeats'])
        ) {
            if ($flight->update($data)) {
                http_response_code(200);
                echo json_encode(array("message" => "Flight has been updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Flight was not updated."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Error: Data is missing."));
        }
    }

    public function delete()
    {
        $this->setHeaders('DELETE');

        $req = $this->startDb();
        $flight = $req['flight'];
        $data = $req['data'];

        if (!empty($data['id'])) {
            if ($flight->delete($data)) {
                http_response_code(200);
                echo json_encode(array("message" => "Flight has been deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Flight was not deleted."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Error: Data is missing."));
        }
    }

    protected function setHeaders($method)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: {$method}");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    protected function startDb()
    {
        include_once('core/bootstrap.php');

        $request = new Request;
        $request->decodeHttpRequest();
        $data = $request->getBody();

        $db = new Database();
        $db->openConnection($dbconfig);

        $flight = new Flight($db);

        return ['flight' => $flight, 'data' => $data];
    }
}