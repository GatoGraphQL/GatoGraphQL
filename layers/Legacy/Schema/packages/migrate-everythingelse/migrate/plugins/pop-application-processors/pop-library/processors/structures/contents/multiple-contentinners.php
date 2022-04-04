<?php

class PoP_Module_Processor_MultipleContentInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const MODULE_CONTENTINNER_PAGECONTENT = 'contentinner-getpop-pagecontent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_PAGECONTENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_CONTENTINNER_PAGECONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_PAGE],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


