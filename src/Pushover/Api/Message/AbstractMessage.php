<?php
namespace Pushover\Api\Message;

use Pushover\Api\Exception;

abstract class AbstractMessage
{
    /** MESSAGE PRIORITY */
    CONST PRIORITY_HIGH_BYPASS_QUIET_CONFIRM    =  2;
    CONST PRIORITY_HIGH_BYPASS_QUIET            =  1;
    CONST PRIORITY_NORMAL                       =  0;
    CONST PRIORITY_LOW_QUIET_NOTIFICATION       = -1;
    CONST PRIORITY_LOWEST_NO_NOTIFICATION       = -2;

    /** MESSAGE SOUNDS */
    CONST SOUND_ALIEN           = 'alien';
    CONST SOUND_BIKE            = 'bike';
    CONST SOUND_BUGLE           = 'bugle';
    CONST SOUND_CASHREGISTER    = 'cashregister';
    CONST SOUND_CLASSICAL       = 'classical';
    CONST SOUND_CLIMB           = 'climb';
    CONST SOUND_COSMIC          = 'cosmic';
    CONST SOUND_ECHO            = 'echo';
    CONST SOUND_FALLING         = 'falling';
    CONST SOUND_GAMELAN         = 'gamelan';
    CONST SOUND_INCOMING        = 'incoming';
    CONST SOUND_INTERMISSION    = 'intermission';
    CONST SOUND_MAGIC           = 'magic ';
    CONST SOUND_MECHANICAL      = 'mechanical';
    CONST SOUND_NONE            = 'none';
    CONST SOUND_PERSISTENT      = 'persistent';
    CONST SOUND_PIANOBAR        = 'pianobar';
    CONST SOUND_PUSHOVER        = 'pushover';
    CONST SOUND_SIREN           = 'siren';
    CONST SOUND_SPACEALARM      = 'spacealarm';
    CONST SOUND_TUGBOAT         = 'tugboat';
    CONST SOUND_UPDOWN          = 'updown';

    /** properties */
    protected $user;
    protected $message;

    protected $device;
    protected $title;
    protected $url;
    protected $url_title;
    protected $priority;
    protected $timestamp;
    protected $sound;

    /**
     * Get ArrayCopy of Object
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param mixed $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }

    /**
     * @return mixed
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param $message
     * @throws \Pushover\Api\Exception
     */
    public function setMessage($message)
    {
        if( strlen($message) > 512 )
            throw new Exception\InvalidMessageException('message is to long - max 512 characters');

        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $priority
     */
    protected function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    /**
     * @return mixed
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param $title
     * @throws \Pushover\Api\Exception\InvalidMessageException
     */
    public function setTitle($title)
    {
        if( $title > 100 )
            throw new Exception\InvalidMessageException('message title to long - max 100 characters');

        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url_title
     */
    public function setUrlTitle($url_title)
    {
        $this->url_title = $url_title;
    }

    /**
     * @return mixed
     */
    public function getUrlTitle()
    {
        return $this->url_title;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}