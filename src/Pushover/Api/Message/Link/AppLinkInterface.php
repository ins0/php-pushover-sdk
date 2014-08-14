<?php
namespace Pushover\Api\Message\Link;

interface AppLinkInterface
{
    static function getLink();
    static function setLink($link);
}