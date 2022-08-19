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
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'blog-dataload-relationalfields-custompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST = 'blog-dataload-relationalfields-postlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST = 'blog-dataload-relationalfields-userlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST = 'blog-dataload-relationalfields-taglist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST = 'blog-dataload-relationalfields-authorpostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST = 'blog-dataload-relationalfields-authorcontentlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST = 'blog-dataload-relationalfields-tagpostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST = 'blog-dataload-relationalfields-tagcontentlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST = 'blog-dataload-relationalfields-singleauthorlist';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST,
        );
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return $this->instanceManager->getInstance(PostObjectTypeResolver::class);

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(\PoP\ComponentModel\Component\Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontentBysingletag($ret);
                break;

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }
    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                if ($categories = gdDataloadAllcontentCategories()) {
                    $ret['category-in'] = $categories;
                }
                break;

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontent($ret);
                break;
        }

        return $ret;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_POSTS];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGS];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORPOSTS];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCONTENT];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGPOSTS];

            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGCONTENT];
        }

        return parent::getFilterSubcomponent($component);
    }
}



