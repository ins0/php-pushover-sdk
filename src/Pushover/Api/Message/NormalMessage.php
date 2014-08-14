<?php
namespace Pushover\Api\Message;

class NormalMessage extends AbstractMessage
{
    protected $priority = AbstractMessage::PRIORITY_NORMAL;

    public function __construct($message = null, $user = null, $device = null)
    {
        if( $message )
            $this->setMessage($message);

        if( $user )
            $this->setUser($user);

        if( $device )
            $this->setDevice($device);
    }
}