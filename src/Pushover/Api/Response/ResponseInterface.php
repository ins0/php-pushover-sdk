<?php
namespace Pushover\Api\Response;

interface ResponseInterface
{
    public function getStatus();
    public function getRequest();
}