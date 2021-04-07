<?php

declare(strict_types=1);

namespace CQ\Request;

use GuzzleHttp\Client;

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

        $response = $client->request($method, $parsedPath->path, [
            'headers' => $headers,
            'query' => $parsedPath->query,
            'json' => $json,
            'form_params' => $form,
        ]);

        $output = $response->getBody()->getContents();

        // Handle NoContent responses
        if (! $output) {
            return (object) [];
        }

        // If output can't be decoded just return output
        return json_decode($output) ?: $output;
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
