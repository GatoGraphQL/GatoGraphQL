<?php

class PoPCore_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-postconclusionsidebar-horizontal';
    public final const COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-subjugatedpostconclusionsidebar-horizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    protected function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];

            case self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return null;
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::COMPONENT_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = $this->getLayoutSubmodule($component);
                break;
        }

        return $ret;
    }
}


