<?php

namespace FwsMailchimp;

use FwsMailchimp\Exception\NoApiKeyException;
use FwsMailchimp\Client\Mailchimp;
use Laminas\Stdlib\Parameters;
use Laminas\Http\PhpEnvironment\RemoteAddress;
use Laminas\Http\Request;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * Abstract mailchimp class
 *
 * @author Garry Childs (Freedom Web Services)
 */
abstract class AbstractMailchimp
{

    const LATEST_API_VERSION = '3.0';

    /**
     *
     * @var string
     */
    protected $apiKey = NULL;

    /**
     *
     * @var string
     */
    protected $apiEndpoint = NULL;

    /**
     *
     * @var string
     */
    protected $listId = NULL;

    /**
     *
     * @var Mailchimp
     */
    protected $client;

    /**
     *
     * @var array
     */
    private $errors = array();

    /**
     *
     * @var Parameters
     */
    protected $parameters;

    /**
     *
     * @var \Laminas\Hydrator\ReflectionHydrator 
     */
    protected $hydrator;

    /**
     *
     * @var string
     */
    private $clientIpAddress;

    /**
     * Initialize class
     * @param Mailchimp $client
     * @param array $config
     * @throws NoApiKeyException
     */
    public function __construct(Mailchimp $client, Array $config)
    {
        $this->client = $client;
        if (isset($config['fwsMailchimp']['apiKey'])) {
            $this->apiKey = $config['fwsMailchimp']['apiKey'];
            $this->client->setApiKey($this->apiKey);
            $apiKeyArray = explode('-', $this->apiKey);
            $this->apiEndpoint = sprintf('https://%s.api.mailchimp.com/%s', end($apiKeyArray), self::LATEST_API_VERSION);
        } else {
            throw new NoApiKeyException('No api key set');
        }

        if (isset($config['fwsMailchimp']['listId'])) {
            $this->setListId($config['fwsMailchimp']['listId']);
        }

        $this->parameters = new Parameters();
        if (class_exists('Laminas\Hydrator\ReflectionHydrator')) {
            $this->hydrator = new \Laminas\Hydrator\ReflectionHydrator();
        } else {
            $this->hydrator = new \Laminas\Hydrator\Reflection();
        }
        
        $this->hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        $remote = new RemoteAddress();
        $this->clientIpAddress = $remote->getIpAddress();
    }

    /**
     * Get the mailchimp list id
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }

    /**
     * Set the mailchimp list id
     * @param string $listId
     * @return \FwsMailchimp\AbstractMailchimp
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     * Set mailchimp parameters
     * @param array $parameters
     */
    protected function setParameters(Array $parameters)
    {
        $this->parameters->fromArray($parameters);
    }

    /**
     * Set mailchimp parameter
     * @param string $name
     * @param mixed $value
     * @return \FwsMailchimp\AbstractMailchimp
     */
    public function __set($name, $value)
    {
        $this->parameters->$name = $value;
        return $this;
    }

    /**
     * Get mailchimp parameter
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->parameters->$name;
    }

    /**
     * Get mailchimp api response body for last call
     * @return array
     */
    protected function getResponse()
    {
        return $this->client->getRespose();
    }

    /**
     * Get errors array
     * @return Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if error(s) occured
     * @return boolean
     */
    public function hasErrors()
    {
        return empty($this->errors);
    }

    /**
     * Clear errors
     * @return \FwsMailchimp\AbstractMailchimp
     */
    public function clearErrors()
    {
        $this->errors = array();
        return $this;
    }

    /**
     * Gets the client IP address
     * @return string
     */
    public function getClientIpAddress()
    {
        return $this->clientIpAddress;
    }

    /**
     * Get mailchimp client class
     * @return Mailchimp
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * md5 hash a string
     * @param string $var
     * @return string
     */
    protected function md5Hash($var)
    {
        return md5(strtolower($var));
    }

    /**
     * Convert array to csv string
     * @param array $array
     * @return string
     */
    protected function arrayToCsv(Array $array)
    {
        return implode(', ', $array);
    }

    /**
     *
     * @return ClassMethods
     */
    protected function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Log error
     * @param string $apiurl
     * @param string $method
     * @param Parameters $parameters
     */
    private function logError($apiurl, $method, Parameters $parameters)
    {
        $this->errors[] = array(
            'apiurl' => $apiurl,
            'method' => $method,
            'parameters' => $parameters->toArray(),
            'response' => $this->getResponse(),
        );
    }

    /**
     * Perform curl GET call
     * @param string $apiurl
     * @return boolean
     */
    protected function get($apiurl)
    {
        return $this->call($apiurl, Request::METHOD_GET, $this->parameters);
    }

    /**
     * Perform curl DELETE call
     * @param string $apiurl
     * @return boolean
     */
    protected function delete($apiurl)
    {
        return $this->call($apiurl, Request::METHOD_DELETE, $this->parameters);
    }

    /**
     * Perform curl POST call
     * @param string $apiurl
     * @return boolean
     */
    protected function post($apiurl)
    {
        return $this->call($apiurl, Request::METHOD_POST, $this->parameters);
    }

    /**
     * Perform curl PATCH call
     * @param string $apiurl
     * @return boolean
     */
    protected function patch($apiurl)
    {
        return $this->call($apiurl, Request::METHOD_PATCH, $this->parameters);
    }

    /**
     * Perform curl PUT call
     * @param string $apiurl
     * @return boolean
     */
    protected function put($apiurl)
    {
        return $this->call($apiurl, Request::METHOD_PUT, $this->parameters);
    }

    /**
     * Call mailchimp api via \FwsMailchimp\Client\Mailchimp
     * @param string $apiurl
     * @param string $method
     * @param Parameters $parameters
     * @return boolean
     */
    private function call($apiurl, $method, Parameters $parameters)
    {
        if ($this->client->call($apiurl, $method, $parameters)) {
            return TRUE;
        } else {
            $this->logError($apiurl, $method, $parameters);
            return FALSE;
        }
    }

}
