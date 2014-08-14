<?php
namespace Pushover\Api\Message\Link;

abstract class AbstractAppLink implements AppLinkInterface
{
    protected static $link;

    static public function getLink()
    {
        return self::$link;
    }

    static public function setLink($link)
    {
        self::setLink($link);
    }
}