<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ComponentProcessors;

use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;

class CustomPostMutationFilterInputContainerComponentProcessor extends CustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS = 'filterinputcontainer-mycustomposts';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT = 'filterinputcontainer-mycustompostcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputComponents(array $component): array
    {
        $targetModule = match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS => [parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST],
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT => [parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT],
            default => null,
        };
        /** @var FilterInputContainerComponentProcessorInterface */
        $targetComponentProcessor = $this->getComponentProcessorManager()->getProcessor($targetModule);
        $targetFilterInputModules = $targetComponentProcessor->getFilterInputComponents($targetModule);
        return array_merge(
            $targetFilterInputModules,
            [
                [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
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
