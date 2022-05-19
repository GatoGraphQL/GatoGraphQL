<?php

define('POP_MULTILAYOUT_HANDLE_AUTOMATEDEMAILS_POSTCONTENT', 'automatedemails-postcontent');

class PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS = 'layout-automatedemails-multiplepost-details';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL = 'layout-automatedemails-multiplepost-thumbnail';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST = 'layout-automatedemails-multiplepost-list';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW = 'layout-automatedemails-multiplepost-simpleview';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW = 'layout-automatedemails-multiplepost-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW],
        );
    }

    public function getDefaultLayoutSubcomponent(array $component)
    {
        $defaults = array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS],
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL],
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST],
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_SimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW],
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts::class, PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST],
        );

        if ($default = $defaults[$component[1]] ?? null) {
            return $default;
        }

        return parent::getDefaultLayoutSubcomponent($component);
    }

    public function getMultipleLayoutSubcomponents(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW:
                $formats = array(
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS => POP_FORMAT_DETAILS,
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL => POP_FORMAT_THUMBNAIL,
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST => POP_FORMAT_LIST,
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW => POP_FORMAT_SIMPLEVIEW,
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW => POP_FORMAT_FULLVIEW,
                );

                $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
                return $multilayout_manager->getLayoutComponents(POP_MULTILAYOUT_HANDLE_AUTOMATEDEMAILS_POSTCONTENT, $formats[$component[1]]);
        }

        return parent::getMultipleLayoutSubcomponents($component);
    }
}



