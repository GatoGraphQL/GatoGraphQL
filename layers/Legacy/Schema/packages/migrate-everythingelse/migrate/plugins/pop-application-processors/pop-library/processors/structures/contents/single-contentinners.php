<?php

class PoP_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_AUTHOR = 'contentinner-author';
    public final const COMPONENT_CONTENTINNER_SINGLE = 'contentinner-single';
    public final const COMPONENT_CONTENTINNER_HIGHLIGHTSINGLE = 'contentinner-highlightsingle';
    public final const COMPONENT_CONTENTINNER_USERPOSTINTERACTION = 'contentinner-userpostinteraction';
    public final const COMPONENT_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION = 'contentinner-userhighlightpostinteraction';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_AUTHOR,
            self::COMPONENT_CONTENTINNER_SINGLE,
            self::COMPONENT_CONTENTINNER_HIGHLIGHTSINGLE,
            self::COMPONENT_CONTENTINNER_USERPOSTINTERACTION,
            self::COMPONENT_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_AUTHOR:
                $ret[] = [PoP_Module_Processor_AuthorContentLayouts::class, PoP_Module_Processor_AuthorContentLayouts::COMPONENT_LAYOUTAUTHOR_CONTENT];
                break;

            case self::COMPONENT_CONTENTINNER_SINGLE:
            case self::COMPONENT_CONTENTINNER_HIGHLIGHTSINGLE:
                $ret[] = [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::COMPONENT_LAYOUT_CONTENT_POST];
                break;

            case self::COMPONENT_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayoutWrappers::class, Wassup_Module_Processor_MultipleComponentLayoutWrappers::COMPONENT_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::COMPONENT_CONTENTINNER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayoutWrappers::class, Wassup_Module_Processor_MultipleComponentLayoutWrappers::COMPONENT_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_HIGHLIGHTSINGLE:
                // Highlights: it has a different set-up
                $this->appendProp($component, $props, 'class', 'well');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


