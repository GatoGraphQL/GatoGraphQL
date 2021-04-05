<?php
namespace PoP\Engine;

interface FunctionAPI
{
    public function redirect($url);
    public function getVersion();
    public function getHomeURL(string $path = ''): string;
    public function getSiteURL(): string;
    public function getHost(): string;
    public function getContentDir();
    public function getContentURL();
    public function getDate($format, $date);
}
