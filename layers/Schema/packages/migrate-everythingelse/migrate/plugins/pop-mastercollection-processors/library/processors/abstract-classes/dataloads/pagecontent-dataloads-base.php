<?php
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;

abstract class PoP_Module_Processor_PageContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getTypeResolverClass(array $module): ?string
    {
        return PageTypeResolver::class;
    }

    public function getPage(array $module, array &$props)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);
        if ($page = $this->getPage($module, $props)) {
            $ret['include'] = [$page];
        }
        return $ret;
    }

    public function getDatasource(array $module, array &$props): string
    {
        return POP_DATALOAD_DATASOURCE_IMMUTABLE;
    }

    protected function getContentSubmodule(array $module)
    {
        return [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_PAGECONTENT];
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);
        $ret[] = $this->getContentSubmodule($module);
        return $ret;
    }
}
