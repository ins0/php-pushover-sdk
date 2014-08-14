<?php
namespace Pushover\Api\Message;

use Pushover\Api\Exception;

class EmergencyMessage extends NormalMessage
{
    protected $priority = AbstractMessage::PRIORITY_HIGH_BYPASS_QUIET_CONFIRM;

    protected $retry = 30;
    protected $expire = 86400;
    protected $callback;

    /**
     * Emergency Message
     *
     * @param string $message
     * @param null $user
     * @param null $device
     * @param null $retry
     * @param null $expire
     * @param null $callback
     */
    public function __construct($message = '', $user = null, $device = null, $retry = null, $expire = null, $callback = null)
    {
        if( $retry )
            $this->setRetry($retry);

        if( $expire )
            $this->setExpire($expire);

        if( $callback )
            $this->setCallback($callback);

        parent::__construct($message, $user, $device);
    }

    /**
     * @param $expire
     * @throws \Pushover\Api\Exception
     */
    public function setExpire($expire)
    {
        if( $expire <= 0 || $expire >= 86400 )
            throw new Exception\InvalidMessageException('expire time need at least 1 and max 86400 seconds');

        $this->expire = $expire;
    }

    /**
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param $retry
     * @throws \Pushover\Api\Exception
     */
    public function setRetry($retry)
    {
        if( $retry < 30 )
            throw new Exception\InvalidMessageException('retry value need at least 30 seconds');

        $this->retry = $retry;
    }

    /**
     * @return int
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * @param $callback
     * @throws \Pushover\Api\Exception
     */
    public function setCallback($callback)
    {
        if( filter_var($callback, FILTER_VALIDATE_URL) === false )
            throw new Exception\InvalidMessageException('invalid callback url');

        $this->callback = $callback;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }
}