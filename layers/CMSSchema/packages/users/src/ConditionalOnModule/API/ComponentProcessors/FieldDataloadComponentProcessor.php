<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors;

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

    public final const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER = 'dataload-relationalfields-singleuser';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_USERLIST = 'dataload-relationalfields-userlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT = 'dataload-relationalfields-usercount';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST = 'dataload-relationalfields-adminuserlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT = 'dataload-relationalfields-adminusercount';

    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT],
        );
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array | null
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
                return $this->getQueriedDBObjectID($componentVariation, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getUserObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return [UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_USERS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT:
                return [UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_USERCOUNT];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return [UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT:
                return [UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT];
        }

        return parent::getFilterSubmodule($componentVariation);
    }
}
