<?php

class GD_URE_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS = 'scrollinner-organizations-details';
    public final const MODULE_SCROLLINNER_INDIVIDUALS_DETAILS = 'scrollinner-individuals-details';
    public final const MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW = 'scrollinner-organizations-fullview';
    public final const MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW = 'scrollinner-individuals-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS:
            case self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS:
            case self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW:
            case self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS => [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS],
            self::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS => [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS],

            self::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW => [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::MODULE_LAYOUT_FULLUSER_ORGANIZATION],
            self::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW => [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::MODULE_LAYOUT_FULLUSER_INDIVIDUAL],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


