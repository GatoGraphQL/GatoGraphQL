<?php

define('POP_MULTILAYOUT_HANDLE_AUTOMATEDEMAILS_POSTCONTENT', 'automatedemails-postcontent');

class PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS = 'layout-automatedemails-multiplepost-details';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL = 'layout-automatedemails-multiplepost-thumbnail';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST = 'layout-automatedemails-multiplepost-list';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW = 'layout-automatedemails-multiplepost-simpleview';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW = 'layout-automatedemails-multiplepost-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW],
        );
    }

    public function getDefaultLayoutSubmodule(array $module)
    {
        $defaults = array(
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS],
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL],
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST],
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW],
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts::class, PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST],
        );

        if ($default = $defaults[$module[1]] ?? null) {
            return $default;
        }

        return parent::getDefaultLayoutSubmodule($module);
    }

    public function getMultipleLayoutSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW:
                $formats = array(
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS => POP_FORMAT_DETAILS,
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL => POP_FORMAT_THUMBNAIL,
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST => POP_FORMAT_LIST,
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW => POP_FORMAT_SIMPLEVIEW,
                    self::MODULE_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW => POP_FORMAT_FULLVIEW,
                );

                $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
                return $multilayout_manager->getLayoutComponentVariations(POP_MULTILAYOUT_HANDLE_AUTOMATEDEMAILS_POSTCONTENT, $formats[$module[1]]);
        }

        return parent::getMultipleLayoutSubmodules($module);
    }
}



