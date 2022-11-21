<?php

return $routes = [
    'GET' => [
        'cities' => 'CityController@read',
        'flights' => 'FlightController@read',
        'flights/cities' => 'FlightController@filter',
        "flights/seats" => 'FlightController@filter'
    ],
    'POST' => [
        'cities' => 'CityController@create',
        'flights' => 'FlightController@create'
    ],
    'PUT' => [
        'cities' => 'CityController@update',
        'flights' => 'FlightController@update'
    ],
    'DELETE' => [
        'cities' => 'CityController@delete',
        'flights' => 'FlightController@delete'
    ]
];