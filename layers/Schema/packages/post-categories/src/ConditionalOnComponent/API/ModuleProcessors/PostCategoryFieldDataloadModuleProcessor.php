<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Categories\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

class PostCategoryFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;

    #[Required]
    public function autowirePostCategoryFieldDataloadModuleProcessor(
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
    ): void {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return $this->postCategoryObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }
}
