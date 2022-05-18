<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CategoryFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_CATEGORIES = 'filterinputcontainer-categories';
    public final const COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT = 'filterinputcontainer-categorycount';
    public final const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES = 'filterinputcontainer-childcategories';
    public final const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT = 'filterinputcontainer-childcategorycount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $categoryFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS],
        ];
        $topLevelCategoryFilterInputModules = [
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES => [
                ...$categoryFilterInputModules,
                ...$topLevelCategoryFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES => [
                ...$categoryFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT => [
                ...$categoryFilterInputModules,
                ...$topLevelCategoryFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT => $categoryFilterInputModules,
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
