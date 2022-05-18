<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ConditionalOnModule\API\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\GenericCustomPosts\ComponentProcessors\GenericCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class GenericCustomPostRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST = 'dataload-relationalfields-genericcustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT = 'dataload-relationalfields-genericcustompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST = 'dataload-relationalfields-admingenericcustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT = 'dataload-relationalfields-admingenericcustompostcount';

    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return $this->getGenericCustomPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
                return [
                    GenericCustomPostFilterInputContainerComponentProcessor::class,
                    GenericCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT:
                return [
                    GenericCustomPostFilterInputContainerComponentProcessor::class,
                    GenericCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTLIST:
                return [
                    GenericCustomPostFilterInputContainerComponentProcessor::class,
                    GenericCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINGENERICCUSTOMPOSTCOUNT:
                return [
                    GenericCustomPostFilterInputContainerComponentProcessor::class,
                    GenericCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT
                ];
        }

        return parent::getFilterSubmodule($component);
    }
}
