<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use Exception;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Cache\CacheInterface;
use PoP\ComponentModel\CheckpointProcessors\CheckpointProcessorManagerInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
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

class Engine extends \PoP\ComponentModel\Engine\Engine implements EngineInterface
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        DataStructureManagerInterface $dataStructureManager,
        InstanceManagerInterface $instanceManager,
        ModelInstanceInterface $modelInstance,
        FeedbackMessageStoreInterface $feedbackMessageStore,
        ModulePathHelpersInterface $modulePathHelpers,
        ModulePathManagerInterface $modulePathManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ModuleFilterManagerInterface $moduleFilterManager,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        CheckpointProcessorManagerInterface $checkpointProcessorManager,
        DataloadHelperServiceInterface $dataloadHelperService,
        EntryModuleManagerInterface $entryModuleManager,
        RequestHelperServiceInterface $requestHelperService,
        protected LooseContractManagerInterface $looseContractManager,
        protected CacheControlEngineInterface $cacheControlEngine,
        ?CacheInterface $persistentCache = null
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $dataStructureManager,
            $instanceManager,
            $modelInstance,
            $feedbackMessageStore,
            $modulePathHelpers,
            $modulePathManager,
            $fieldQueryInterpreter,
            $moduleFilterManager,
            $moduleProcessorManager,
            $checkpointProcessorManager,
            $dataloadHelperService,
            $entryModuleManager,
            $requestHelperService,
            $persistentCache,
        );
    }

    public function generateData(): void
    {
        // Check if there are hooks that must be implemented by the CMS, that have not been done so.
        // Check here, since we can't rely on addAction('popcms:init') to check, since we don't know if it was implemented!
        if ($notImplementedHooks = $this->looseContractManager->getNotImplementedRequiredHooks()) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('The following hooks have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->translationAPI->__('", "'), $notImplementedHooks)
                )
            );
        }
        if ($notImplementedNames = $this->looseContractManager->getNotImplementedRequiredNames()) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('The following names have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->translationAPI->__('", "'), $notImplementedNames)
                )
            );
        }

        parent::generateData();
    }

    public function outputResponse(): void
    {
        // 1. Generate the data
        $this->generateData();

        // 2. Get the data, and ask the formatter to output it
        $formatter = $this->dataStructureManager->getDataStructureFormatter();

        // If CacheControl is enabled, add it to the headers
        $headers = [];
        if (CacheControlComponent::isEnabled()) {
            if ($cacheControlHeader = $this->cacheControlEngine->getCacheControlHeader()) {
                $headers[] = $cacheControlHeader;
            }
        }
        $data = $this->getOutputData();
        $formatter->outputResponse($data, $headers);
    }
}
