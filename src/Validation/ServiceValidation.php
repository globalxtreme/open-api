<?php

namespace GlobalXtreme\OpenAPI\Validation;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class ServiceValidation
{
    const CLIENT = 'default';

    const URI = [
        'BASE' => 'open-api/'
    ];


    /** --- PROTECTED FUNCTIONS --- */

    /**
     * @param $url
     * @param $payload
     * @param $method
     *
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function call($url, $payload = [], $method = 'get')
    {
        try {

            // Create a guzzle client
            $client = new Client();

            // Forward the request and get the response.
            $response = $client->request($method, $url, self::prepare($payload));

            // Set response body
            $body = json_decode($response->getBody());

            // Check http status code
            $statusCode = $response->getStatusCode();
            if ($statusCode != 200) {
                if (optional($body)->status) {
                    $status = $body->status;
                    error(['code' => $status->code, 'msg' => $status->message], $status->internalMsg, $statusCode, $status->attributes);
                }

                errOccurred();
            }

            // Response with json
            return response()->json($body, $statusCode);

        } catch (BadResponseException $e) {

            $body = json_decode($e->getResponse()->getBody());
            if (optional($body)->status) {
                $status = $body->status;
                error(['code' => $status->code, 'msg' => $status->message], $status->internalMsg, $e->getResponse()->getStatusCode(), $status->attributes);
            }

            errOccurred("", $e->getResponse()->getStatusCode());
        }
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected static function host()
    {
        return config('open-api.clients.' . static::CLIENT . '.host');
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws \ErrorException
     */
    protected static function prepare(array $payload)
    {
        // Default options
        $options = [];

        // Set headers
        $options['headers'] = self::setHeaders();

        // Set content type
        $options['json'] = $payload;

        return $options;
    }

    /**
     * @return array
     * @throws \ErrorException
     */
    protected static function setHeaders()
    {
        $client = config('open-api.clients.' . static::CLIENT);
        if (!$client) {
            error(['code' => "OA0001", 'msg' => "Open API client invalid"]);
        }

        return [
            'Client-ID' => $client['client-id'],
            'Client-Name' => $client['client-name'],
            'Client-Secret' => $client['client-secret'],
        ];
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected static function setURL($path)
    {
        return static::host() . static::URI['BASE'] . $path;
    }

}
