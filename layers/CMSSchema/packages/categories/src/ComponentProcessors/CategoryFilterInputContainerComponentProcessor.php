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

    public function getFilterInputComponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $categoryFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
            new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS),
        ];
        $topLevelCategoryFilterInputComponents = [
            new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES => [
                ...$categoryFilterInputComponents,
                ...$topLevelCategoryFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES => [
                ...$categoryFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT => [
                ...$categoryFilterInputComponents,
                ...$topLevelCategoryFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT => $categoryFilterInputComponents,
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
