<?php

namespace FwsMailchimp\Client;

use Laminas\Stdlib\Parameters;
use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\Curl;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Json\Json;

/**
 * Mailchimp Client Class
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Mailchimp
{
    const ENC_JSON = 'application/json';

    /**
     * 
     * @var Response|null
     */
    private ?Response $response;

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
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     *
     * @param string $apiurl
     * @param string $method
     * @param Parameters $parameters @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     * @return boolean
     */
    public function call($apiurl, $method, Parameters $parameters)
    {
        $client = new Client($apiurl, array(
            'adapter' => Curl::class,
        ));
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
     * @return array|null
     */
    public function getRespose(): ?array
    {
        if ($this->response instanceof Response) {
            return Json::decode($this->response->getBody(), true);
        }
        return null;
    }

}
