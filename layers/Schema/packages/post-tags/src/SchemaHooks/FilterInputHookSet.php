<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\SchemaHooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Routing\RouteHookNames;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Posts\ModuleProcessors\FilterInputContainerModuleProcessor;
use PoPSchema\PostTags\ComponentConfiguration;
use PoPSchema\Tags\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        protected FilterInputContainerModuleProcessor $filterInputContainerModuleProcessor,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
        );
    }
    
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            $this->filterInputContainerModuleProcessor->getFilterInputHookName(),
            [$this, 'getFilterInputSubmodules']
        );
    }

    public function getFilterInputSubmodules(array $filterInputSubmodules): array
    {
        return [
            ...$filterInputSubmodules,
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_TAG_SLUGS
            ],
        ];
    }
}
