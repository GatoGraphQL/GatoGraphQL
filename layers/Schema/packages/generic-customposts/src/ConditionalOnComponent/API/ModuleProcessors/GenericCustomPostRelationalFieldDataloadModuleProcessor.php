<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\GenericCustomPosts\ModuleProcessors\GenericCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\GenericCustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use Symfony\Contracts\Service\Attribute\Required;

class GenericCustomPostRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST = 'dataload-relationalfields-genericcustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT = 'dataload-relationalfields-genericcustompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST = 'dataload-relationalfields-admingenericcustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT = 'dataload-relationalfields-admingenericcustompostcount';
    
    protected GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver;
    protected ListQueryInputOutputHandler $listQueryInputOutputHandler;

    #[Required]
    final public function autowireGenericCustomPostRelationalFieldDataloadModuleProcessor(
        GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver,
        ListQueryInputOutputHandler $listQueryInputOutputHandler,
    ): void {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return $this->genericCustomPostObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return $this->listQueryInputOutputHandler;
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
                return [
                    GenericCustomPostFilterInputContainerModuleProcessor::class,
                    GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT:
                return [
                    GenericCustomPostFilterInputContainerModuleProcessor::class,
                    GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return [
                    GenericCustomPostFilterInputContainerModuleProcessor::class,
                    GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT:
                return [
                    GenericCustomPostFilterInputContainerModuleProcessor::class,
                    GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT
                ];
        }

        return parent::getFilterSubmodule($module);
    }
}
