<?php
namespace PoP\Application;

abstract class HelperAPI_Base implements HelperAPI
{
    public function __construct()
    {
        HelperAPIFactory::setInstance($this);
    }

    public function escapeHTML(string $text) {

    	return htmlspecialchars($text);
    }

    public function makeClickable(string $text) {

		// Taken from https://stackoverflow.com/questions/5341168/best-way-to-make-links-clickable-in-block-of-text
    	return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
    }

    public function convertLinebreaksToHTML(string $text): string {

    	return nl2br($text);
    }
}
