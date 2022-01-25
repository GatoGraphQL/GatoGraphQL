<?php

declare(strict_types=1);

namespace PoP\Site\Engine;

use PoP\Application\Engine\Engine as UpstreamEngine;
use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;

class Engine extends UpstreamEngine
{
    private ?ApplicationStateHelperServiceInterface $applicationStateHelperService = null;

    final public function setApplicationStateHelperService(ApplicationStateHelperServiceInterface $applicationStateHelperService): void
    {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }
    final protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        return $this->applicationStateHelperService ??= $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
    }

    public function generateDataAndPrepareResponse(): void
    {
        // If doing JSON, the response from the parent is already adequate
        if ($this->getApplicationStateHelperService()->doingJSON()) {
            parent::generateDataAndPrepareResponse();
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
