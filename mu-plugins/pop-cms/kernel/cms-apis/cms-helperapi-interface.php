<?php
namespace PoP\CMS;

interface HelperAPI
{
    public function addQueryArgs(array $key_values, string $url);
    public function removeQueryArgs(array $keys, string $url);
    public function escapeHTML(string $text);
    public function escapeAttributes(string $text);
    public function makeClickable(string $text);
    public function convertLinebreaksToHTML(string $text);
    public function sanitizeUsername(string $username);
    public function sanitizeTitle(string $title);
    public function maybeAddTrailingSlash(string $text);
}