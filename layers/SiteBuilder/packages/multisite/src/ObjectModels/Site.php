<?php

declare(strict_types=1);

namespace PoP\Multisite\ObjectModels;

use PoP\Engine\Facades\CMS\CMSServiceFacade;

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
            $cmsService = CMSServiceFacade::getInstance();
            $domain = $cmsService->getHomeURL();
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
    // public function getName(): string {
    //     return $this->name;
    // }
    // public function getDescription() {
    //     return $this->description;
    // }
}
