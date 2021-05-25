<?php
namespace PoP\Engine;

interface FunctionAPI
{
    // DateFormatter
    public function getDate($format, $date);
    
    // Move to ConfigurationComponentModel
    public function redirect($url);
    public function getVersion();
    public function getHost(): string;
    public function getContentDir();
    public function getContentURL();
}
