<?php
namespace Pushover\Api\Response;

interface ResponseInterface
{
    public function exchangeArray($array);
    public function getStatusCode();
    public function setStatusCode($statusCode);
}