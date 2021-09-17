<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ModuleProcessors;

use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ModulePathHelpersInterface $modulePathHelpers,
        ModuleFilterManagerInterface $moduleFilterManager,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        CMSServiceInterface $cmsService,
        NameResolverInterface $nameResolver,
        DataloadHelperServiceInterface $dataloadHelperService,
        RequestHelperServiceInterface $requestHelperService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $modulePathHelpers,
            $moduleFilterManager,
            $moduleProcessorManager,
            $cmsService,
            $nameResolver,
            $dataloadHelperService,
            $requestHelperService,
        );
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return QueryRoot::ID;
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return MutationRoot::ID;
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return $graphQLSchemaDefinitionService->getQueryRootTypeResolverClass();
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $graphQLSchemaDefinitionService->getMutationRootTypeResolverClass();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
