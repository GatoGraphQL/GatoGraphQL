<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ComponentProcessors;

use PoPCMSSchema\Media\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class MediaFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMS = 'filterinputcontainer-media-items';
    public final const COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMCOUNT = 'filterinputcontainer-media-item-count';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $mediaFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_MIME_TYPES],
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMS => [
                ...$mediaFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMCOUNT => [
                ...$mediaFilterInputComponents,
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
