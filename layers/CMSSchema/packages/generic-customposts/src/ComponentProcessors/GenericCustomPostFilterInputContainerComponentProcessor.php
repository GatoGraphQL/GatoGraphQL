<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\GenericCustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class GenericCustomPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST = 'filterinputcontainer-genericcustompostlist';
    public final const MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-genericcustompostcount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST = 'filterinputcontainer-admingenericcustompostlist';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-admingenericcustompostcount';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getFilterInputComponentVariations(array $componentVariation): array
    {
        $genericCustomPostFilterInputModules = [
            ...$this->getIDFilterInputComponentVariations(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SEARCH],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        ];
        $adminGenericCustomPostFilterInputModules = [
            [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponentVariations();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST=> [
                ...$genericCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST => [
                    ...$genericCustomPostFilterInputModules,
                    ...$adminGenericCustomPostFilterInputModules,
                    ...$paginationFilterInputModules,
                ],
            self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT => $genericCustomPostFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT => [
                ...$genericCustomPostFilterInputModules,
                ...$adminGenericCustomPostFilterInputModules,
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
