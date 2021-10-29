<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST = 'dataload-relationalfields-singlecustompost';
    public const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST = 'dataload-relationalfields-unioncustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-unioncustompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST = 'dataload-relationalfields-adminunioncustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-adminunioncustompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'dataload-relationalfields-custompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT = 'dataload-relationalfields-custompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST = 'dataload-relationalfields-admincustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT = 'dataload-relationalfields-admincustompostcount';

    protected CustomPostUnionTypeResolver $customPostUnionTypeResolver;
    protected ListQueryInputOutputHandler $listQueryInputOutputHandler;

    #[Required]
    final public function autowireCustomPostRelationalFieldDataloadModuleProcessor(
        CustomPostUnionTypeResolver $customPostUnionTypeResolver,
        ListQueryInputOutputHandler $listQueryInputOutputHandler,
    ): void {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }

    public function getModulesToProcess(): array
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

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->customPostUnionTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->listQueryInputOutputHandler;
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT
                ];
        }

        return parent::getFilterSubmodule($module);
    }
}
