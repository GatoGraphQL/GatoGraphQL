<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

abstract class AbstractPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public final const COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT = 'filterinputcontainer-postcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS = 'filterinputcontainer-adminposts';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT = 'filterinputcontainer-adminpostcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_POSTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $postFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
        ];
        $statusFilterInputComponents = [
            new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_POSTS => [
                ...$postFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT => $postFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS => [
                ...$postFilterInputComponents,
                ...$paginationFilterInputComponents,
                ...$statusFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT => [
                ...$postFilterInputComponents,
                ...$statusFilterInputComponents,
            ],
            default => [],
        };
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
