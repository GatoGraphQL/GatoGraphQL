<?php
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Blog_Module_Processor_FieldDataloads extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'blog-dataload-relationalfields-custompostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST = 'blog-dataload-relationalfields-postlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_USERLIST = 'blog-dataload-relationalfields-userlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST = 'blog-dataload-relationalfields-taglist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST = 'blog-dataload-relationalfields-authorpostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST = 'blog-dataload-relationalfields-authorcontentlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST = 'blog-dataload-relationalfields-tagpostlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST = 'blog-dataload-relationalfields-tagcontentlist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST = 'blog-dataload-relationalfields-singleauthorlist';

    public function getComponentsToProcess(): array
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

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return $this->instanceManager->getInstance(PostObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
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
    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
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

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
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

        return parent::getFilterSubmodule($component);
    }
}



