<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;

class CustomPostMutationFilterInputContainerComponentProcessor extends CustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS = 'filterinputcontainer-mycustomposts';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT = 'filterinputcontainer-mycustompostcount';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS,
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT,
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     *
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        $targetComponent = match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTS => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST),
            self::COMPONENT_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT =>new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT),
            default => null,
        };
        if ($targetComponent === null) {
            return [];
        }
        /** @var FilterInputContainerComponentProcessorInterface */
        $targetComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($targetComponent);
        $targetFilterInputComponents = $targetComponentProcessor->getFilterInputComponents($targetComponent);
        return array_merge(
            $targetFilterInputComponents,
            [
                new Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
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
