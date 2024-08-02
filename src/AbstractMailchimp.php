<?php

namespace FwsMailchimp;

use FwsMailchimp\Exception\NoApiKeyException;
use FwsMailchimp\Client\Mailchimp;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Stdlib\Parameters;
use Laminas\Http\PhpEnvironment\RemoteAddress;
use Laminas\Http\Request;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Laminas\Stdlib\ParametersInterface;
use stdClass;

/**
 * Abstract mailchimp class
 *
 * @author Garry Childs (Freedom Web Services)
 */
abstract class AbstractMailchimp
{

    public const LATEST_API_VERSION = '3.0';

    protected string|null $apiKey = null;

    protected string|null $apiEndpoint = null;

    protected string|null $listId = null;

    /**
     *
     * @var stdClass[]
     */
    private array $errors = [];

    protected ParametersInterface $parameters;

    protected ReflectionHydrator $hydrator;

    private string $clientIpAddress = '';

    /**
     * @param Mailchimp $client
     * @param array<string, mixed> $config
     * @throws NoApiKeyException
     */
    public function __construct(
        protected Mailchimp $client,
        array $config
    )
    {
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
        $this->hydrator = new ReflectionHydrator();
        
        $this->hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        $remote = new RemoteAddress();
        $this->clientIpAddress = $remote->getIpAddress();
    }

    /**
     * Get the mailchimp list id
     * @return string|null
     */
    public function getListId(): string|null
    {
        return $this->listId;
    }

    /**
     * Set the mailchimp list id
     * @param string|null $listId
     * @return AbstractMailchimp
     */
    public function setListId(?string $listId): AbstractMailchimp
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     * Set mailchimp parameters
     * @param array<string, mixed> $parameters
     * @return AbstractMailchimp
     */
    protected function setParameters(array $parameters): AbstractMailchimp
    {
        $this->parameters->fromArray($parameters);
        return $this;
    }

    /**
     * Set mailchimp parameter
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value): void
    {
        $this->parameters->$name = $value;
    }

    /**
     * Get mailchimp parameter
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->parameters->$name;
    }

    /**
     * Get mailchimp api response body for last call
     * @return array
     */
    protected function getResponse(): array
    {
        return $this->client->getResponse();
    }

    /**
     * Get errors array
     * @return stdClass[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Determine if error(s) occurred
     * @return bool
     */
    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    /**
     * Clear errors
     * @return AbstractMailchimp
     */
    public function clearErrors(): AbstractMailchimp
    {
        $this->errors = [];
        return $this;
    }

    /**
     * Gets the client IP address
     * @return string
     */
    public function getClientIpAddress(): string
    {
        return $this->clientIpAddress;
    }

    /**
     * Get mailchimp client class
     * @return Mailchimp
     */
    protected function getClient(): Mailchimp
    {
        return $this->client;
    }

    /**
     * md5 hash a string
     * @param string $var
     * @return string
     */
    protected function md5Hash(string $var): string
    {
        return md5(strtolower($var));
    }

    /**
     * Convert array to csv string
     * @param array<string|int, mixed> $array
     * @return string
     */
    protected function arrayToCsv(array $array): string
    {
        return implode(', ', $array);
    }

    /**
     * Get reflection hydrator
     * @return ReflectionHydrator
     */
    protected function getHydrator(): ReflectionHydrator
    {
        return $this->hydrator;
    }

    /**
     * Log error
     * @param string $apiUrl
     * @param string $method
     * @param ParametersInterface<string, mixed> $parameters
     */
    private function logError(string $apiUrl, string $method, ParametersInterface $parameters): void
    {
        $this->errors[] = (object) [
            'apiUrl' => $apiUrl,
            'method' => $method,
            'parameters' => $parameters->toArray(),
            'response' => $this->getResponse(),
        ];
    }

    /**
     * Perform curl GET call
     * @param string $apiUrl
     * @return boolean
     */
    protected function get(string $apiUrl): bool
    {
        return $this->call($apiUrl, Request::METHOD_GET, $this->parameters);
    }

    /**
     * Perform curl DELETE call
     * @param string $apiUrl
     * @return boolean
     */
    protected function delete(string $apiUrl): bool
    {
        return $this->call($apiUrl, Request::METHOD_DELETE, $this->parameters);
    }

    /**
     * Perform curl POST call
     * @param string $apiUrl
     * @return boolean
     */
    protected function post(string $apiUrl): bool
    {
        return $this->call($apiUrl, Request::METHOD_POST, $this->parameters);
    }

    /**
     * Perform curl PATCH call
     * @param string $apiUrl
     * @return boolean
     */
    protected function patch(string $apiUrl): bool
    {
        return $this->call($apiUrl, Request::METHOD_PATCH, $this->parameters);
    }

    /**
     * Perform curl PUT call
     * @param string $apiUrl
     * @return boolean
     */
    protected function put(string $apiUrl): bool
    {
        return $this->call($apiUrl, Request::METHOD_PUT, $this->parameters);
    }

    /**
     * Call mailchimp api via \FwsMailchimp\Client\Mailchimp
     * @param string $apiUrl
     * @param string $method
     * @param ParametersInterface<string, mixed> $parameters
     * @return boolean
     */
    private function call(string $apiUrl, string $method, ParametersInterface $parameters): bool
    {
        if ($this->client->call($apiUrl, $method, $parameters)) {
            return true;
        } else {
            $this->logError($apiUrl, $method, $parameters);
            return false;
        }
    }

}
