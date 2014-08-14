<?php
namespace Pushover\Api\Message\Link;

class TwitterLink extends AbstractAppLink
{
    static public function directMessage($screenName)
    {
        self::setLink('direct_message?screen_name=' . $screenName);
    }

    static public function setLink($part)
    {
        $link = sprintf('twitter://%s', $part );
        parent::setLink($link);
    }
}