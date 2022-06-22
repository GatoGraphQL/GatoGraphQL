<?php

class PoPCore_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-postconclusionsidebar-horizontal';
    public final const COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-subjugatedpostconclusionsidebar-horizontal';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
            self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
        );
    }

    protected function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];

            case self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return null;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = $this->getLayoutSubcomponent($component);
                break;
        }

        return $ret;
    }
}


