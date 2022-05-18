<?php
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

abstract class PoP_Module_Processor_PageContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }

    public function getPage(array $componentVariation, array &$props)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);
        if ($page = $this->getPage($componentVariation, $props)) {
            $ret['include'] = [$page];
        }
        return $ret;
    }

    public function getDatasource(array $componentVariation, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    protected function getContentSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_PAGECONTENT];
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);
        $ret[] = $this->getContentSubmodule($componentVariation);
        return $ret;
    }
}
