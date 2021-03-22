<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectModels;

class Site
{
    private string $id;
    private string $domain;
    private string $host;
    // private string $name;
    // private string $description;
    public function __construct(
        string $domain = ''
        /*, $name, $description = ''*/
    ) {
        if (!$domain) {
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            $domain = $cmsengineapi->getHomeURL();
        }
        $this->domain = $domain;
        $this->host = removeScheme($domain);
        $this->id = $this->host;
        // $this->name = $name;
        // $this->description = $description;
    }
    public function getID(): string
    {
        return $this->id;
    }
    public function getDomain(): string
    {
        return $this->domain;
    }
    public function getHost(): string
    {
        return $this->host;
    }
    // public function getName() {
    //     return $this->name;
    // }
    // public function getDescription() {
    //     return $this->description;
    // }
}
