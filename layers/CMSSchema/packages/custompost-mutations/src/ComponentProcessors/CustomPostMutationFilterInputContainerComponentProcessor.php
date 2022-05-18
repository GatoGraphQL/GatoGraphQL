<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ComponentProcessors;

use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;

class CustomPostMutationFilterInputContainerComponentProcessor extends CustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS = 'filterinputcontainer-mycustomposts';
    public final const MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT = 'filterinputcontainer-mycustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputModules(array $componentVariation): array
    {
        $targetModule = match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST],
            self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT],
            default => null,
        };
        /** @var FilterInputContainerComponentProcessorInterface */
        $targetComponentProcessor = $this->getComponentProcessorManager()->getProcessor($targetModule);
        $targetFilterInputModules = $targetComponentProcessor->getFilterInputModules($targetModule);
        return array_merge(
            $targetFilterInputModules,
            [
                [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ]
        );
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}
