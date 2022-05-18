<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class UserFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public final const COMPONENT_FILTERINPUTCONTAINER_USERCOUNT = 'filterinputcontainer-usercount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS = 'filterinputcontainer-adminusers';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT = 'filterinputcontainer-adminusercount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_USERS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $userFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_NAME],
        ];
        $adminUserFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EMAILS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_USERS => [
                ...$userFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS => [
                ...$userFilterInputModules,
                ...$adminUserFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT => $userFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT => [
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
