<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ComponentProcessors;

use PoPCMSSchema\Categories\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
use PoP\ComponentModel\Component\Component;

class CategoryFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_CATEGORIES = 'filterinputcontainer-categories';
    public final const COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT = 'filterinputcontainer-categorycount';
    public final const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES = 'filterinputcontainer-childcategories';
    public final const COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT = 'filterinputcontainer-childcategorycount';
    public final const COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES = 'filterinputcontainer-genericcategories';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORIES,
            self::COMPONENT_FILTERINPUTCONTAINER_CATEGORYCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORIES,
            self::COMPONENT_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        $categoryFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS),
        ];
        $topLevelCategoryFilterInputComponents = [
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID),
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
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCATEGORIES => [
                new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY),
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
