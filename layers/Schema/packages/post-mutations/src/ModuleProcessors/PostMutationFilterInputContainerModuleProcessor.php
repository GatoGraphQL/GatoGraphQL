<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;
use PoPSchema\Posts\ModuleProcessors\AbstractPostFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;

class PostMutationFilterInputContainerModuleProcessor extends AbstractPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_MYPOST = 'filterinputcontainer-mypost';
    public const MODULE_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public const MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT = 'filterinputcontainer-mypostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputModules(array $module): array
    {
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYPOST => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            self::MODULE_FILTERINPUTCONTAINER_MYPOSTS => [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTS],
            self::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT => [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            default => null,
        };
        if ($targetModule[0] === self::class) {
            $filterInputModules = parent::getFilterInputModules($targetModule);
        } else {
            /** @var FilterInputContainerModuleProcessorInterface */
            $targetModuleProcessor = $this->getModuleProcessorManager()->getProcessor($targetModule);
            $filterInputModules = $targetModuleProcessor->getFilterInputModules($targetModule);
        }
        $filterInputModules[] = [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS];
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
