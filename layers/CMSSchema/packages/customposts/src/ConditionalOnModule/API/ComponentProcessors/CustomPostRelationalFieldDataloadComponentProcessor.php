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

    public final const MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST = 'dataload-relationalfields-singlecustompost';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST = 'dataload-relationalfields-unioncustompostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-unioncustompostcount';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST = 'dataload-relationalfields-adminunioncustompostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-adminunioncustompostcount';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'dataload-relationalfields-custompostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT = 'dataload-relationalfields-custompostcount';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST = 'dataload-relationalfields-admincustompostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT = 'dataload-relationalfields-admincustompostcount';

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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT],
        );
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array | null
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
                return $this->getQueriedDBObjectID($componentVariation, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getCustomPostUnionTypeResolver();
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT
                ];
        }

        return parent::getFilterSubmodule($componentVariation);
    }
}
