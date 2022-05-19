<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class TagFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_TAGS = 'filterinputcontainer-tags';
    public final const COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT = 'filterinputcontainer-tagcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_TAGS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $tagFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS],
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
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
