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

    /** @var  Response */
    private $response;

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
     * @return bool
     */
    public function getReceiptStatus($receiptToken)
    {
        return $this->callApi($this::REQUEST_GET, '/receipts/' . $receiptToken);
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
        $this->callMultiApi($this::REQUEST_POST, '/messages', $messages);


        // flush response
        $this->setResponse(null);

        // return results
        return count($errors) <= 0 ? true : $errors;
    }

    /**
     * Send Push Message
     *
     * @param AbstractMessage $message
     * @return bool|Response
     */
    public function push(AbstractMessage $message)
    {
        return $this->callApi($this::REQUEST_POST, '/messages', $message->getArrayCopy());
    }

    /**
     * Client Call to API
     *
     * @param string $method
     * @param $resource
     * @param array $data
     * @return bool
     * @throws Api\Exception\InvalidResponseException
     * @throws Api\Exception\RequestFailedException
     */
    private function callApi($method = self::REQUEST_POST, $resource, $data = array())
    {
        // get client
        $client = $this->getClient();

        // send request
        $response = $client->sendRequest(
            $method,
            $data,
            sprintf('%s/%s%s.json', self::API_ENDPOINT, self::API_VERSION, $resource),
            $this->getAuthentication()
        );

        return $this->handleResponse($response);
    }

    /**
     * Send Bulk Request
     *
     * @param string $method
     * @param $resource
     * @param array $data
     * @return mixed
     */
    private function callMultiApi($method = self::REQUEST_POST, $resource, $data = array())
    {
        // get client
        $client = $this->getClient();

        $client->preMultiRequest();

        // send request
        foreach($data as $message)
        {
            if( $message instanceof AbstractMessage )
            {
                $client->onMultiRequest(
                    $method,
                    $message->getArrayCopy(),
                    sprintf('%s/%s%s.json', self::API_ENDPOINT, self::API_VERSION, $resource),
                    $this->getAuthentication()
                );
            }
        }

        // post connection
        $responseData = $client->postMultiRequest();

        print_r($responseData);
        die();

        foreach($responseData as $response)
        {
            $success = $this->hydrateResponseData($response);
            if( !$success )
            {
                // get response
            }
        }
    }

    /**
     * Handle Response
     *
     * @param $response
     * @return bool
     * @throws Api\Exception\InvalidResponseException
     */
    private function handleResponse($response)
    {
        // decode response
        $json = json_decode($response, true);
        if( $json )
        {
            $response = $this->hydrateResponseData($json);
            $this->setResponse($response);

            if( !$response->getStatus() )
            {
                return false;
            }

            return true;
        }

        throw new InvalidResponseException('API responses in an invalid/not know format');
    }

    /**
     * Hydrate API Response Data
     *
     * @param $responseData
     * @return Response
     */
    private function hydrateResponseData($responseData)
    {
        if( isset($responseData['acknowledged']) )
        {
            $response = new ReceiptResponse();
            $response->setStatus($responseData['status']);
            $response->setRequest($responseData['request']);
            $response->setAcknowledged($responseData['acknowledged']);
            $response->setAcknowledgedAt($responseData['acknowledged_at']);
            $response->setAcknowledgedBy($responseData['acknowledged_by']);
            $response->setLastDeliveredAt($responseData['last_delivered_at']);
            $response->setExpired($responseData['expired']);
            $response->setExpiresAt($responseData['expires_at']);
            $response->setCalledBack($responseData['called_back']);
            $response->setCalledBackAt($responseData['called_back_at']);

        } else {

            $response = new Response();
            $response->setStatus($responseData['status']);
            $response->setRequest($responseData['request']);

            // receipt
            if( isset($responseData['receipt']) )
            {
                $response->setReceipt($responseData['receipt']);
            }

            // errors
            if( isset($responseData['errors']) )
            {
                $response->setErrors($responseData['errors']);
            }
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return \Pushover\Api\Response\Response
     */
    public function getResponse()
    {
        return $this->response;
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