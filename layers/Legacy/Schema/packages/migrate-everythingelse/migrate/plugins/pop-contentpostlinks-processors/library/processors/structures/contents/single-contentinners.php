<?php

class PoP_ContentPostLinks_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_CONTENTINNER_LINKSINGLE = 'contentinner-linksingle';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_LINKSINGLE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_LINKSINGLE:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::class, PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::MODULE_LAYOUT_CONTENT_LINK];
                break;
        }

        return $ret;
    }
}


