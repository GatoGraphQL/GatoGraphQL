<?php

class PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS = 'layout-automatedemails-postauthors';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS];
                break;
        }

        return $ret;
    }
}



