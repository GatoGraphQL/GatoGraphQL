<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class UserFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public final const MODULE_FILTERINPUTCONTAINER_USERCOUNT = 'filterinputcontainer-usercount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINUSERS = 'filterinputcontainer-adminusers';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT = 'filterinputcontainer-adminusercount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_USERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_USERCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT],
        );
    }

    public function getFilterInputComponentVariations(array $componentVariation): array
    {
        $userFilterInputModules = [
            ...$this->getIDFilterInputComponentVariations(),
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_NAME],
        ];
        $adminUserFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_EMAILS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponentVariations();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_USERS => [
                ...$userFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS => [
                ...$userFilterInputModules,
                ...$adminUserFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_USERCOUNT => $userFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT => [
                ...$userFilterInputModules,
                ...$adminUserFilterInputModules,
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
