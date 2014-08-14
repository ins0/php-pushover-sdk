<?php
namespace Pushover\Api\Message;

class SilentMessage extends NormalMessage
{
    protected $priority = AbstractMessage::PRIORITY_LOWEST_NO_NOTIFICATION;
}