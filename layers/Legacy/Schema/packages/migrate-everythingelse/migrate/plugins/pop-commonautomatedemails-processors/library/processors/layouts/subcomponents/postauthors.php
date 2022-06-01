<?php

class PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS = 'layout-automatedemails-postauthors';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS];
                break;
        }

        return $ret;
    }
}



