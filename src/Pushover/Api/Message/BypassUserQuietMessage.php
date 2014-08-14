<?php
namespace Pushover\Api\Message;

class BypassUserQuietMessage extends NormalMessage
{
    protected $priority = AbstractMessage::PRIORITY_HIGH_BYPASS_QUIET;
}