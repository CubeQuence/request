<?php

declare(strict_types=1);

namespace CQ\Request;

use CQ\Request\Exceptions\BadResponseException;
use CQ\Request\Exceptions\ConnectException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;

final class Request
{
    public static function send(
        string $method,
        string $path,
        array | null $json = null,
        array | null $form = null,
        array | null $headers = null
    ): object {
        $client = new Client();
        $parsedPath = self::parsePath($path);

        try {
            $response = $client->request($method, $parsedPath->path, [
                'headers' => $headers,
                'query' => $parsedPath->query,
                'json' => $json,
                'form_params' => $form,
            ]);
        } catch (GuzzleBadResponseException $error) {
            $response = $error->getResponse();

            throw new BadResponseException(
                message: $response->getBody()->getContents(),
                code: $response->getStatusCode(),
                previous: $error
            );
        } catch (GuzzleConnectException $error) {
            throw new ConnectException(
                message: $error->getMessage(),
                code: $error->getCode(),
                previous: $error
            );
        }

        // Response with content
        if ($output = $response->getBody()->getContents()) {
            return json_decode($output);
        }

        // Response without content
        return (object) [];
    }

    /**
     * Check if path contains `?foo=bar`
     * and place them in the query array
     */
    private static function parsePath(string $path): object
    {
        $query = null;

        if (strpos($path, '?') !== false) {
            [$path, $query] = explode('?', $path, 2);
        }

        return (object) [
            'path' => $path,
            'query' => $query,
        ];
    }
}
