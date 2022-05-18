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
        $postFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
        ];
        $statusFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_POSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT => $postFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
                ...$statusFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT => [
                ...$postFilterInputModules,
                ...$statusFilterInputModules,
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
