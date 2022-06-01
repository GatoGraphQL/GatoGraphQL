<?php

class PoP_ContentPostLinks_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_LINKSINGLE = 'contentinner-linksingle';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_LINKSINGLE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_LINKSINGLE:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::class, PoP_ContentPostLinks_Module_Processor_LinkContentLayouts::COMPONENT_LAYOUT_CONTENT_LINK];
                break;
        }

        return $ret;
    }
}


