<?php
namespace Pushover\Api\Client;

use Pushover\Api\Authentication\AuthenticationInterface;

interface ClientInterface
{
    public function sendRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication);
    public function getResponseStatusCode();
    public function setResponseStatusCode($statusCode);
}