<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;

class PostMutationFilterInputContainerComponentProcessor extends AbstractPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public final const MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT = 'filterinputcontainer-mypostcount';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputComponentVariations(array $componentVariation): array
    {
        $targetModule = match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYPOSTS => [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTS],
            self::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT => [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            default => null,
        };
        $filterInputModules = parent::getFilterInputComponentVariations($targetModule);
        $filterInputModules[] = [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS];
        return $filterInputModules;
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
