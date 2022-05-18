<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class TagFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_TAGS = 'filterinputcontainer-tags';
    public final const MODULE_FILTERINPUTCONTAINER_TAGCOUNT = 'filterinputcontainer-tagcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $tagFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUGS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::MODULE_FILTERINPUTCONTAINER_TAGS => [
                ...$tagFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGCOUNT => $tagFilterInputModules,
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
