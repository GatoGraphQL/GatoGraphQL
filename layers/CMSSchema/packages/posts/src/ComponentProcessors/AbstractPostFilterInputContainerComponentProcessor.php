<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

abstract class AbstractPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public final const MODULE_FILTERINPUTCONTAINER_POSTCOUNT = 'filterinputcontainer-postcount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINPOSTS = 'filterinputcontainer-adminposts';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT = 'filterinputcontainer-adminpostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT],
        );
    }

    public function getFilterInputComponentVariations(array $componentVariation): array
    {
        $postFilterInputModules = [
            ...$this->getIDFilterInputComponentVariations(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SEARCH],
        ];
        $statusFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponentVariations();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_POSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT => $postFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
                ...$statusFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT => [
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
