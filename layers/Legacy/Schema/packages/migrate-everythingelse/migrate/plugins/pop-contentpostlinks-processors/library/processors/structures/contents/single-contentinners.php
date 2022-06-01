<?php

class PoP_ContentPostLinks_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_LINKSINGLE = 'contentinner-linksingle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_LINKSINGLE],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTENTINNER_LINKSINGLE:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::class, PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::COMPONENT_LAYOUT_CONTENT_LINK];
                break;
        }

        return $ret;
    }
}


