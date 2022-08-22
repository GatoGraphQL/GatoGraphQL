<?php
define('POP_MULTILAYOUT_HANDLE_POSTCONTENT', 'postcontent');
define('POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT', 'authorpostcontent');
define('POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT', 'singlepostcontent');
define('POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT', 'postabovecontent');

class PoP_Module_Processor_MultiplePostLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR = 'layout-multiplepost-navigator';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS = 'layout-multiplepost-addons';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS = 'layout-multiplepost-details';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL = 'layout-multiplepost-thumbnail';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_LIST = 'layout-multiplepost-list';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_LINE = 'layout-multiplepost-line';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED = 'layout-multiplepost-related';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT = 'layout-multiplepost-edit';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW = 'layout-multiplepost-simpleview';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW = 'layout-multiplepost-fullview';
    public final const COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW = 'layout-authormultiplepost-fullview';
    public final const COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW = 'layout-singlemultiplepost-fullview';
    public final const COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT = 'layout-multiplepost-simpleview-abovecontent';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_LIST,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW,
            self::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW,
            self::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW,
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT,
        );
    }

    public function getDefaultLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $defaults = array(
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_NAVIGATOR],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_ADDONS],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_DETAILS],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_THUMBNAIL],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_LIST => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_LIST],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_LINE],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_RELATED],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_EDIT],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW],
            self::COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_POST],
            self::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST],
            self::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_SINGLELAYOUT_FULLVIEW_POST],
        );

        if ($default = $defaults[$component->name] ?? null) {
            return $default;
        }

        return parent::getDefaultLayoutSubcomponent($component);
    }

    public function getMultipleLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_LIST:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW:
            case self::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW:
            case self::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW:
            case self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT:
                $handles = array(
                    self::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW => POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT,
                    self::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW => POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT => POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT,
                );
                $handle = $handles[$component->name] ?? POP_MULTILAYOUT_HANDLE_POSTCONTENT;

                $formats = array(
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR => POP_FORMAT_NAVIGATOR,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS => POP_FORMAT_ADDONS,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS => POP_FORMAT_DETAILS,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL => POP_FORMAT_THUMBNAIL,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_LIST => POP_FORMAT_LIST,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE => POP_FORMAT_LINE,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED => POP_FORMAT_LIST,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT => POP_FORMAT_TABLE,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW => POP_FORMAT_SIMPLEVIEW,
                    self::COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW => POP_FORMAT_FULLVIEW,
                    self::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW => POP_FORMAT_FULLVIEW,
                    self::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW => POP_FORMAT_FULLVIEW,
                );
                $format = $formats[$component->name] ?? '';

                $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
                return $multilayout_manager->getLayoutComponents($handle, $format);
        }

        return parent::getMultipleLayoutSubcomponents($component);
    }
}



