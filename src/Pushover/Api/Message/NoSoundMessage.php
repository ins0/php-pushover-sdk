<?php
namespace Pushover\Api\Message;

class NoSoundMessage extends NormalMessage
{
    protected $priority = AbstractMessage::PRIORITY_LOW_QUIET_NOTIFICATION;
}