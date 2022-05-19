<?php

class GD_URE_Module_Processor_CustomContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_URE_CONTENTINNER_MEMBER = 'ure-contentinner-member';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_CONTENTINNER_MEMBER],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_CONTENTINNER_MEMBER:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_HEADER];
                break;
        }

        return $ret;
    }
}


