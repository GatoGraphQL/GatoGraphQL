<?php
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class PoP_Module_Processor_PageContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }

    public function getPage(array $component, array &$props)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);
        if ($page = $this->getPage($component, $props)) {
            $ret['include'] = [$page];
        }
        return $ret;
    }

    public function getDatasource(array $component, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    protected function getContentSubcomponent(array $component)
    {
        return [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_PAGECONTENT];
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);
        $ret[] = $this->getContentSubcomponent($component);
        return $ret;
    }
}
