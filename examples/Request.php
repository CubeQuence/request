<?php

declare(strict_types=1);

use CQ\Request\Exceptions\BadResponseException;
use CQ\Request\Exceptions\ConnectException;
use CQ\Request\Request;

try {
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
} catch (BadResponseException $error) {
    echo $error->getMessage();  // HTTP response body
    echo $error->getCode();     // HTTP response code

    exit;
} catch (ConnectException $error) { // Error occured while connecting to the server
    echo $error->getMessage();

    exit;
}

echo json_encode($response);
