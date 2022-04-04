<?php

class PoP_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT = 'postconclusion-sidebarmulticomponent-left';
    public final const MODULE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT = 'subjugatedpostconclusion-sidebarmulticomponent-left';
    public final const MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT = 'postconclusion-sidebarmulticomponent-right';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT],
            [self::class, self::MODULE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT],
            [self::class, self::MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_POSTSOCIALMEDIA_POSTWRAPPER];
                break;

            case self::MODULE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;

            case self::MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:
                $ret[] = [PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::MODULE_LAYOUT_SIMPLEPOSTAUTHORS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:
                $this->appendProp([PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::MODULE_LAYOUT_SIMPLEPOSTAUTHORS], $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



