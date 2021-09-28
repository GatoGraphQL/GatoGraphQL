<?php

declare(strict_types=1);

namespace PoP\Site\Engine;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Cache\CacheInterface;
use PoP\ComponentModel\CheckpointProcessors\CheckpointProcessorManagerInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\Translation\TranslationAPIInterface;

class Engine extends \PoP\Application\Engine\Engine
{
    protected ApplicationStateHelperServiceInterface $applicationStateHelperService;
    public function __construct(
        ApplicationStateHelperServiceInterface $applicationStateHelperService
    ) {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }

    public function outputResponse(): void
    {
        // If doing JSON, the response from the parent is already adequate
        if ($this->applicationStateHelperService->doingJSON()) {
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
