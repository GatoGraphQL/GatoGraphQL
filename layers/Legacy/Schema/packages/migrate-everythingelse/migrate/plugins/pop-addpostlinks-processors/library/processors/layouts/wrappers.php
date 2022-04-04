<?php

class PoP_AddPostLinks_Module_Processor_LayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE = 'layoutwrapper-addpostlinks-linkframevisible';
    public final const MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED = 'layoutwrapper-addpostlinks-linkframecollapsed';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE],
            [self::class, self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE:
                $ret[] = [PoP_AddPostLinks_Module_Processor_LinkFrameLayouts::class, PoP_AddPostLinks_Module_Processor_LinkFrameLayouts::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE];
                break;

            case self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED:
                $ret[] = [PoP_AddPostLinks_Module_Processor_LinkFrameLayouts::class, PoP_AddPostLinks_Module_Processor_LinkFrameLayouts::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE:
            case self::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED:
                return 'hasLink';
        }

        return null;
    }
}



