<?php
namespace Pushover;

use Pushover\Api\Authentication\AuthenticationInterface;
use Pushover\Api\Client\ClientInterface;
use Pushover\Api\Client\CurlClient;
use Pushover\Api\Exception\InvalidResponseException;
use Pushover\Api\Exception;
use Pushover\Api\Message\AbstractMessage;
use Pushover\Api\Response\ReceiptResponse;
use Pushover\Api\Response\Response;
use Pushover\Api\Response\ResponseInterface;

class Api
{
    CONST API_ENDPOINT      = 'https://api.pushover.net';
    CONST API_VERSION       = 1;

    CONST REQUEST_POST      = 'POST';
    CONST REQUEST_GET       = 'GET';

    /** @var  AuthenticationInterface */
    private $authentication;

    /** @var  ClientInterface */
    private $client;

    /** @var array  */
    private $responseSet = array();

    /** @var array  */
    private $responseErrors = array();

    /** @var bool */
    private $isError = false;

    /**
     * Constructor
     *
     * @param AuthenticationInterface $authentication
     * @param ClientInterface $client
     */
    public function __construct(AuthenticationInterface $authentication, ClientInterface $client = null)
    {
        // set client
        if( $client === null )
        {
            $client = new CurlClient();
        }

        // set auth
        $this->setAuthentication($authentication);

        // set client
        $this->setClient($client);
    }

    /**
     * Get Receipt Status
     *
     * @param $receiptToken
     * @return bool|mixed
     */
    public function getReceiptStatus($receiptToken)
    {
        $this->callApi($this::REQUEST_GET, '/receipts/' . $receiptToken, null, new ReceiptResponse());

        return $this->isError() ? false : $this->getResponse();
    }

    /**
     * Send Bulk Push Messages
     *
     * @param $messages
     * @return array|bool
     */
    public function bulkPush($messages)
    {
        if( !is_array($messages) || count($messages) <= 0)
            return false;

        // send data
        $this->callApi($this::REQUEST_POST, '/messages', $messages, new Response());

        // return results
        return $this->isError() ? false : $this->getResponseSet();
    }

    /**
     * Send Push Message
     *
     * @param AbstractMessage $message
     * @return bool|Response
     */
    public function push(AbstractMessage $message)
    {
        $this->callApi($this::REQUEST_POST, '/messages', array($message), new Response());
        return $this->isError() ? false : $this->getResponse();
    }

    /**
     * Client Call to API
     *
     * @param string $method
     * @param $resource
     * @param array $data
     * @param ResponseInterface $responseHydrator
     * @return bool
     */
    private function callApi($method = self::REQUEST_POST, $resource, $data = array(), ResponseInterface $responseHydrator = null)
    {
        if( !is_array($data) )
            $data = array($data);

        // flush old response and errors
        $this->setResponseErrors(array());
        $this->setResponseSet(array());

        // get client
        $client = $this->getClient();
        $client->onClientConnect();

        // send request
        foreach($data as $dataSend)
        {
            $responseData = $client->sendRequest(
                $method,
                $dataSend ? $dataSend->getArrayCopy() : array(),
                sprintf('%s/%s%s.json', self::API_ENDPOINT, self::API_VERSION, $resource),
                $this->getAuthentication()
            );

            $this->handleResponse($responseData, $client->getResponseStatusCode(), clone $responseHydrator);
        }

        // client close
        $client->onClientClose();

        return true;
    }

    /**
     * Handle Response
     *
     * @param $response
     * @param $responseStatusCode
     * @param ResponseInterface $responseHydrator
     * @return ResponseInterface
     * @throws Api\Exception\InvalidResponseException
     */
    private function handleResponse($response, $responseStatusCode, ResponseInterface $responseHydrator)
    {
        // decode response
        $json = json_decode($response, true);
        if( $json )
        {
            /** @var ResponseInterface $response */
            $response = $responseHydrator->exchangeArray($json);
            $response->setStatusCode($responseStatusCode);

            // is error
            if( $responseStatusCode !== 200 )
            {
                $this->addResponseError($response);
            } else {
                $this->addResponse($response);
            }

            return true;
        }

        throw new InvalidResponseException('API responses in an invalid/not know format');
    }

    /**
     * @param $responseErrors
     * @return $this
     */
    private function setResponseErrors($responseErrors)
    {
        $this->responseErrors = $responseErrors;
        return $this;
    }

    /**
     * @param $error
     */
    private function addResponseError($error)
    {
        $this->isError = true;
        $this->responseErrors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->responseErrors;
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @param ResponseInterface $response
     * @return $this
     */
    private function addResponse(ResponseInterface $response)
    {
        $this->responseSet[] = $response;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseSet()
    {
        return $this->responseSet;
    }

    /**
     * Get Single Response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return current($this->getResponseSet());
    }

    /**
     * @param $responseSet
     * @return $this
     */
    public function setResponseSet($responseSet)
    {
        $this->responseSet = $responseSet;
        return $this;
    }

    /**
     * @param \Pushover\Api\Authentication\AuthenticationInterface $authentication
     */
    public function setAuthentication(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return \Pushover\Api\Authentication\AuthenticationInterface
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param \Pushover\Api\Client\ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Pushover\Api\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}