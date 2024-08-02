<?php

namespace FwsMailchimp\Client;

use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\Curl;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Json\Json;
use Laminas\Stdlib\ParametersInterface;
use stdClass;

/**
 * Mailchimp Client Class
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Mailchimp
{
    public const ENC_JSON = 'application/json';

    /**
     * 
     * @var Response|null
     */
    private ?Response $response = null;

    /**
     * 
     * @var string|null
     */
    private ?string $apiKey = null;

    /**
     *
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     *
     * @param string $apiUrl
     * @param string $method
     * @param ParametersInterface<string, mixed> $parameters @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     * @return boolean
     */
    public function call(string $apiUrl, string $method, ParametersInterface $parameters): bool
    {
        $client = new Client($apiUrl, ['adapter' => Curl::class]);
        $client->setEncType(self::ENC_JSON);
        $client->setMethod($method);
        $client->setAuth('user', $this->apiKey);
        $client->getAdapter()->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
        switch ($method) {
            case Request::METHOD_GET:
                $client->setParameterGet($parameters->toArray());
                break;
            case Request::METHOD_POST:
            case Request::METHOD_PUT:
            case Request::METHOD_PATCH:
                $client->setRawBody(Json::encode($parameters->toArray()));
                break;
        }
        $this->response = $client->send();
        return $this->response->isSuccess();
    }

    /**
     * Get the response body as an array
     * @return array
     */
    public function getResponse(): array
    {
        if ($this->response instanceof Response) {
            return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
        }
        return [];
    }

}
