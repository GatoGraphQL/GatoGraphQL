<?php

class PoPCore_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public const MODULE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-postconclusionsidebar-horizontal';
    public const MODULE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'contentinner-subjugatedpostconclusionsidebar-horizontal';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    protected function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];

            case self::MODULE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return null;
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::MODULE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = $this->getLayoutSubmodule($module);
                break;
        }

        return $ret;
    }
}


