<?php

declare(strict_types=1);

use CQ\Request\Request;

$response = Request::send(
    method: 'GET',
    path: 'https://jsonplaceholder.typicode.com/users/1',
    // json: [
    //     'filter' => 'age',
    // ],
    // form: [
    //     'csrf_token' => '1234',
    // ],
    // headers: [
    //     'Authorization' => 'Bearer 1234',
    // ]
);

echo json_encode($response);
