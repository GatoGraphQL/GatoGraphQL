<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS = 'scrollinner-automatedemails-latestcontent-details';
    public const MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW = 'scrollinner-automatedemails-latestcontent-simpleview';
    public const MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW = 'scrollinner-automatedemails-latestcontent-fullview';
    public const MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL = 'scrollinner-automatedemails-latestcontent-thumbnail';
    public const MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST = 'scrollinner-automatedemails-latestcontent-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return HooksAPIFacade::getInstance()->applyFilters(
                    POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST:
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
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW:
                // Add an extra space at the bottom of each post
                $this->appendProp($module, $props, 'class', 'email-scrollelem-post');
        }

        parent::initModelProps($module, $props);
    }
}


