<?php
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class PoP_Module_Processor_PageContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }

    public function getPage(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);
        if ($page = $this->getPage($component, $props)) {
            $ret['include'] = [$page];
        }
        return $ret;
    }

    public function getDatasource(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    protected function getContentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_PAGECONTENT];
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);
        $ret[] = $this->getContentSubcomponent($component);
        return $ret;
    }
}
