<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class TagFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_TAGS = 'filterinputcontainer-tags';
    public final const COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT = 'filterinputcontainer-tagcount';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_TAGS,
            self::COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT,
        );
    }

    public function getFilterInputComponents(Component $component): array
    {
        $tagFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_TAGS => [
                ...$tagFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT => $tagFilterInputComponents,
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
