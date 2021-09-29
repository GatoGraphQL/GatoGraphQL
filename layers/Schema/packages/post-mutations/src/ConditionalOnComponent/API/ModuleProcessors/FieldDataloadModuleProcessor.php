<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ConditionalOnComponent\API\ModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST = 'dataload-relationalfields-mypostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT = 'dataload-relationalfields-mypostcount';
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected ListQueryInputOutputHandler $listQueryInputOutputHandler;

    #[Required]
    public function autowireFieldDataloadModuleProcessor(
        PostObjectTypeResolver $postObjectTypeResolver,
        ListQueryInputOutputHandler $listQueryInputOutputHandler,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
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
                return $this->postObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return $this->listQueryInputOutputHandler;
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
