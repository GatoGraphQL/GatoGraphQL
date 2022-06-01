<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class UserFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public final const COMPONENT_FILTERINPUTCONTAINER_USERCOUNT = 'filterinputcontainer-usercount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS = 'filterinputcontainer-adminusers';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT = 'filterinputcontainer-adminusercount';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_USERS,
            self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
        );
    }

    public function getFilterInputComponents(Component $component): array
    {
        $userFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_NAME),
        ];
        $adminUserFilterInputComponents = [
            new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EMAILS),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_USERS => [
                ...$userFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS => [
                ...$userFilterInputComponents,
                ...$adminUserFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT => $userFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT => [
                ...$userFilterInputComponents,
                ...$adminUserFilterInputComponents,
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
