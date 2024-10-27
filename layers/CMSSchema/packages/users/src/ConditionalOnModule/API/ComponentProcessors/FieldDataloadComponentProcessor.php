<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Users\ComponentProcessors\UserFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER = 'dataload-relationalfields-singleuser';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST = 'dataload-relationalfields-userlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT = 'dataload-relationalfields-usercount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST = 'dataload-relationalfields-adminuserlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT = 'dataload-relationalfields-adminusercount';

    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        if ($this->listQueryInputOutputHandler === null) {
            /** @var ListQueryInputOutputHandler */
            $listQueryInputOutputHandler = $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
            $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
        }
        return $this->listQueryInputOutputHandler;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT,
        );
    }

    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    public function getObjectIDOrIDs(Component $component, array &$props, array &$data_properties): string|int|array|null
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
                return $this->getQueriedDBObjectID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getUserObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_USERS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT);
        }

        return parent::getFilterSubcomponent($component);
    }
}
