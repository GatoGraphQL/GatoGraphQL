<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ComponentProcessors;

use PoPCMSSchema\Media\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class MediaFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_MEDIAITEMS = 'filterinputcontainer-media-items';
    public final const MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT = 'filterinputcontainer-media-item-count';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT],
        );
    }

    public function getFilterInputModules(array $componentVariation): array
    {
        $mediaFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SEARCH],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_MIME_TYPES],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS => [
                ...$mediaFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT => [
                ...$mediaFilterInputModules,
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
