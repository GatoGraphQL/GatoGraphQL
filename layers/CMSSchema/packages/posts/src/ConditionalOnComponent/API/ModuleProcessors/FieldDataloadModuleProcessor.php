<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnComponent\API\ModuleProcessors;

use PoPAPI\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ModuleProcessors\PostFilterInputContainerModuleProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public final const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST = 'dataload-relationalfields-singlepost';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST = 'dataload-relationalfields-postlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT = 'dataload-relationalfields-postcount';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST = 'dataload-relationalfields-adminpostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT = 'dataload-relationalfields-adminpostcount';

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array | null
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
                return [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTCOUNT];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
                return [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
