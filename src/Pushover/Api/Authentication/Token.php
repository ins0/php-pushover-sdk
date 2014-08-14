<?php
namespace Pushover\Api\Authentication;

class Token implements AuthenticationInterface
{
    private $accessToken = null;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getCredential()
    {
        return $this->accessToken;
    }
}