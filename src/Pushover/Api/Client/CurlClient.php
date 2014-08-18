<?php
namespace Pushover\Api\Client;

use Pushover\Api\Authentication\AuthenticationInterface;
use Pushover\Api\Authentication\Token;
use Pushover\Api\Exception;

class CurlClient implements ClientInterface
{
    /** @var  StatusCode */
    private $lastResponseStatusCode;

    private $curlMultiHandle;
    private $curlHandles = array();

    public function __construct()
    {
        if( !function_exists('curl_init') )
        {
            throw new Exception('curl lib functions not found');
        }
    }

    public function preMultiRequest()
    {
        if( $this->curlMultiHandle )
        {
            // connection set all fine
            return true;
        }

        $this->curlMultiHandle = curl_multi_init();
        return $this;
    }

    public function onMultiRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication)
    {
        // prepare endpoint data
        $data = array_filter($data);

        if( $authentication instanceof Token )
            $data['token'] = $authentication->getCredential();
        else
            throw new Exception('authentication requires tokenauth');

        $endpoint = sprintf('%s?%s', $endpoint, http_build_query ($data));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        //debug
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');


        curl_multi_add_handle($this->curlMultiHandle, $ch);
        array_push($this->curlHandles, $ch);

        return true;
    }

    public function postMultiRequest()
    {
        $response = array();
        do {
            curl_multi_exec($this->curlMultiHandle, $running);
            curl_multi_select($this->curlMultiHandle);
            usleep(100);
        } while ($running > 0);

        // get results
        foreach ($this->curlHandles as $ch) {
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response[] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($this->curlMultiHandle, $ch);
        }
        $this->curlHandles = array();

        // close multi
        curl_multi_close($this->curlMultiHandle);
        return $response;
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
            throw new Exception('authentication requires tokenauth');

        $endpoint = sprintf('%s?%s', $endpoint, http_build_query ($data));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

        $sResult = curl_exec($ch);
        if (curl_errno($ch))
        {
            throw new Exception(curl_error($ch));
        }

        $this->setResponseStatusCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        curl_close($ch);

        return $sResult;
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