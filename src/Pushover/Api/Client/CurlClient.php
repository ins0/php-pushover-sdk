<?php
namespace Pushover\Api\Client;

use Pushover\Api\Authentication\AuthenticationInterface;
use Pushover\Api\Authentication\Token;
use Pushover\Api\Exception;

class CurlClient implements ClientInterface
{
    /** @var  StatusCode */
    private $lastResponseStatusCode;

    /** @var  Resource CurlHandle */
    private $curlHandle;

    public function __construct()
    {
        if( !function_exists('curl_init') )
        {
            throw new Exception('curl lib functions not found');
        }
    }

    /**
     * On Client Start Connection
     *
     * @return $this
     */
    public function onClientConnect()
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER,1);
        //debug
        //curl_setopt($curlHandle, CURLOPT_PROXY, '127.0.0.1:8888');
        $this->curlHandle = $curlHandle;

        return $this;
    }

    /**
     * Send Request
     *
     * @param $method
     * @param array $data
     * @param $endpoint
     * @param AuthenticationInterface $authentication
     * @return mixed
     * @throws \Pushover\Api\Exception
     */
    public function sendRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication)
    {
        // prepare endpoint data
        $data = array_filter($data);

        if( $authentication instanceof Token )
            $data['token'] = $authentication->getCredential();
        else
            throw new Exception('authentication requires auth token');

        $endpoint = sprintf('%s?%s', $endpoint, http_build_query ($data));

        $ch = $this->curlHandle;
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $sResult = curl_exec($ch);
        if (curl_errno($ch))
        {
            throw new Exception(curl_error($ch));
        }

        $this->setResponseStatusCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        return $sResult;
    }

    /**
     * On Client Close Connection
     *
     * @return $this
     */
    public function onClientClose()
    {
        curl_close($this->curlHandle);
        return $this;
    }

    /**
     * Return last HTTP Status Code
     *
     * @return StatusCode
     */
    public function getResponseStatusCode()
    {
        return $this->lastResponseStatusCode;
    }

    /**
     * Set last HTTP Status Code
     *
     * @param $statusCode
     * @return $this
     */
    public function setResponseStatusCode($statusCode)
    {
        $this->lastResponseStatusCode = $statusCode;
        return $this;
    }
}