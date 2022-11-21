<?php

class CityController
{
    public function read()
    {
        $this->setHeaders('GET');

        $req = $this->startDb();
        $city = $req['city'];
        $data = $req['data'];

        $recordset = $city->selectAll();

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
        $city = $req['city'];
        $data = $req['data'];

        if (
            !empty($data['name'])
        ) {
            if ($city->create($data)) {
                http_response_code(201);
                echo json_encode(array("message" => "A new city has been added."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "City was not added."));
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
        $city = $req['city'];
        $data = $req['data'];

        if (!empty($data['id']) && !empty($data['name'])) {
            if ($city->update($data)) {
                http_response_code(200);
                echo json_encode(array("message" => "City has been updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "City was not updated."));
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
        $city = $req['city'];
        $data = $req['data'];

        if (!empty($data['id'])) {
            if ($city->delete($data)) {
                http_response_code(200);
                echo json_encode(array("message" => "City has been deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "City was not deleted."));
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

        $city = new City($db);

        return ['city' => $city, 'data' => $data];
    }
}