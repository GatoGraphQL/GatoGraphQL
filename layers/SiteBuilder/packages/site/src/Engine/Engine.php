<?php

declare(strict_types=1);

namespace PoP\Site\Engine;

use PoP\Application\Engine\Engine as UpstreamEngine;
use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use Symfony\Contracts\Service\Attribute\Required;

class Engine extends UpstreamEngine
{
    private ?ApplicationStateHelperServiceInterface $applicationStateHelperService = null;

    public function setApplicationStateHelperService(ApplicationStateHelperServiceInterface $applicationStateHelperService): void
    {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }
    protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        return $this->applicationStateHelperService ??= $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
    }

    public function outputResponse(): void
    {
        // If doing JSON, the response from the parent is already adequate
        if ($this->getApplicationStateHelperService()->doingJSON()) {
            parent::outputResponse();
            return;
        }

        // Before anything: check if to do a redirect, and exit
        $this->maybeRedirectAndExit();

        // 1. Generate the data
        $this->generateData();

        // 2. Print the HTML
        // Code implemented maybe in pop-engine-htmlcssplatform/templates/index.php
        echo '<html><body>TODO</body></html>';
    }
}
