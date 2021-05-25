<?php
namespace PoP\Engine;

interface FunctionAPI
{
    public function redirect($url);
    public function getVersion();
    public function getHost(): string;
    public function getContentDir();
    public function getContentURL();
}
