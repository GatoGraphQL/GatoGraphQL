<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ConditionalOnModule\API\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class CustomPostRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST = 'dataload-relationalfields-singlecustompost';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST = 'dataload-relationalfields-unioncustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-unioncustompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST = 'dataload-relationalfields-adminunioncustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-adminunioncustompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'dataload-relationalfields-custompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT = 'dataload-relationalfields-custompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST = 'dataload-relationalfields-admincustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT = 'dataload-relationalfields-admincustompostcount';

    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
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
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT],
        );
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getCustomPostUnionTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
                ];
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT
                ];
        }

        return parent::getFilterSubcomponent($component);
    }
}
