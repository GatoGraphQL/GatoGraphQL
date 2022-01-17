<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;

class CustomPostMutationFilterInputContainerModuleProcessor extends CustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS = 'filterinputcontainer-mycustomposts';
    public const MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT = 'filterinputcontainer-mycustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputModules(array $module): array
    {
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST],
            self::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT],
            default => null,
        };
        /** @var FilterInputContainerModuleProcessorInterface */
        $targetModuleProcessor = $this->getModuleProcessorManager()->getProcessor($targetModule);
        $targetFilterInputModules = $targetModuleProcessor->getFilterInputModules($targetModule);
        return array_merge(
            $targetFilterInputModules,
            [
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ]
        );
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
