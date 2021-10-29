<?php
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Blog_Module_Processor_FieldDataloads extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'blog-dataload-relationalfields-custompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST = 'blog-dataload-relationalfields-postlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_USERLIST = 'blog-dataload-relationalfields-userlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST = 'blog-dataload-relationalfields-taglist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST = 'blog-dataload-relationalfields-authorpostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST = 'blog-dataload-relationalfields-authorcontentlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST = 'blog-dataload-relationalfields-tagpostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST = 'blog-dataload-relationalfields-tagcontentlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST = 'blog-dataload-relationalfields-singleauthorlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST],
        );
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return $this->getInstanceManager()->getInstance(PostObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return $this->getInstanceManager()->getInstance(UserObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getInstanceManager()->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->getInstanceManager()->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->getInstanceManager()->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontentBysingletag($ret);
                break;

            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }
    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                if ($categories = gdDataloadAllcontentCategories()) {
                    $ret['category-in'] = $categories;
                }
                break;

            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontent($ret);
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_POSTS];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGS];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORPOSTS];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCONTENT];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGPOSTS];

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGCONTENT];
        }

        return parent::getFilterSubmodule($module);
    }
}



