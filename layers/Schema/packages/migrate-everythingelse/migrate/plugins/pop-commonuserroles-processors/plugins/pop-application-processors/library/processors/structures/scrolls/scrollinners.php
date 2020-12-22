<?php

class GD_URE_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS = 'scrollinner-organizations-details';
    public const MODULE_SCROLLINNER_INDIVIDUALS_DETAILS = 'scrollinner-individuals-details';
    public const MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW = 'scrollinner-organizations-fullview';
    public const MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW = 'scrollinner-individuals-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS:
            case self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS:
            case self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW:
            case self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS => [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS],
            self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS => [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS],

            self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW => [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::MODULE_LAYOUT_FULLUSER_ORGANIZATION],
            self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW => [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::MODULE_LAYOUT_FULLUSER_INDIVIDUAL],
        );

        if ($layout = $layouts[$module[1]]) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


