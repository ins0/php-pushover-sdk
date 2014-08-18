<?php
namespace Pushover\Api\Client;

use Pushover\Api\Authentication\AuthenticationInterface;

interface ClientInterface
{
    public function onClientConnect();
    public function sendRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication);
    public function onClientClose();

    public function getResponseStatusCode();
    public function setResponseStatusCode($statusCode);
}