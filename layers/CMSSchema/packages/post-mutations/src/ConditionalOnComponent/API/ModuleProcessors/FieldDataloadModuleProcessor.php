<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnComponent\API\ModuleProcessors;

use PoPAPI\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public final const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST = 'dataload-relationalfields-mypostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT = 'dataload-relationalfields-mypostcount';

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
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT],
        );
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
