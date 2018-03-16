<?php

namespace NotificationChannels\Discord;

use Exception;
use Illuminate\Support\Arr;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\Discord\Exceptions\CouldNotSendNotification;

class Discord
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /***
     * @var string
     */
    protected $token;

    /**
     * Discord constructor.
     *
     * @param HttpClient $http
     * @param            $token
     */
    public function __construct(HttpClient $http, $token)
    {
        $this->httpClient = $http;
        $this->token = $token;
    }

    /**
     * @param       $channel
     * @param array $data
     *
     * @return array
     * @throws CouldNotSendNotification
     */
    public function send($channel, array $data)
    {
        return $this->request('POST', $channel, $data);
    }

    /**
     * @param       $verb
     * @param       $url
     * @param array $data
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function request($verb, $url, array $data)
    {
        try {
            $response = $this->httpClient->request($verb, $url, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => $data
            ]);
        } catch (RequestException $exception) {
            if ($response = $exception->getResponse()) {
                throw CouldNotSendNotification::serviceRespondedWithAnHttpError($response);
            }

            throw CouldNotSendNotification::serviceCommunicationError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceCommunicationError($exception);
        }

        $body = json_decode($response->getBody(), true);

        if (Arr::get($body, 'code', 0) > 0) {
            throw CouldNotSendNotification::serviceRespondedWithAnApiError($body);
        }

        return $body;
    }
}
