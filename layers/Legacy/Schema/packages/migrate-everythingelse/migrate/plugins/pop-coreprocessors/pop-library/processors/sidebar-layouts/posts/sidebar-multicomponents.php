<?php

class PoP_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT = 'postconclusion-sidebarmulticomponent-left';
    public final const MODULE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT = 'subjugatedpostconclusion-sidebarmulticomponent-left';
    public final const MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT = 'postconclusion-sidebarmulticomponent-right';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT],
            [self::class, self::COMPONENT_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT],
            [self::class, self::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                break;

            case self::COMPONENT_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;

            case self::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:
                $ret[] = [PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:
                $this->appendProp([PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS], $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



