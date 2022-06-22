<?php

class GD_URE_Module_Processor_CustomContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_URE_CONTENTINNER_MEMBER = 'ure-contentinner-member';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_CONTENTINNER_MEMBER,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_URE_CONTENTINNER_MEMBER:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_HEADER];
                break;
        }

        return $ret;
    }
}


