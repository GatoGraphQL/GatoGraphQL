<?php

define('POP_MULTILAYOUT_HANDLE_USERCONTENT', 'usercontent');
define('POP_MULTILAYOUT_HANDLE_USERPOPOVER', 'userpopover');
define('POP_MULTILAYOUT_HANDLE_USERPOSTAUTHOR', 'userpostauthor');
define('POP_MULTILAYOUT_HANDLE_USERCONTEXTUALPOSTAUTHOR', 'usercontextualpostauthor');

class PoP_Module_Processor_MultipleUserLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const MODULE_LAYOUT_MULTIPLEUSER_POPOVER = 'layout-multipleuser-popover';
    public final const MODULE_LAYOUT_MULTIPLEUSER_POSTAUTHOR = 'layout-multipleuser-postauthor';
    public final const MODULE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR = 'layout-multipleuser-contextualpostauthor';
    public final const MODULE_LAYOUT_MULTIPLEUSER_NAVIGATOR = 'layout-multipleuser-navigator';
    public final const MODULE_LAYOUT_MULTIPLEUSER_ADDONS = 'layout-multipleuser-addons';
    public final const MODULE_LAYOUT_MULTIPLEUSER_DETAILS = 'layout-multipleuser-details';
    public final const MODULE_LAYOUT_MULTIPLEUSER_THUMBNAIL = 'layout-multipleuser-thumbnail';
    public final const MODULE_LAYOUT_MULTIPLEUSER_LIST = 'layout-multipleuser-list';
    public final const MODULE_LAYOUT_MULTIPLEUSER_FULLUSER = 'layout-multipleuser-fulluser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_POPOVER],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_POSTAUTHOR],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR],

            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_LIST],
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER],
        );
    }

    public function getDefaultLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_POPOVER:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_POSTAUTHOR:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR:
                return [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_NAVIGATOR:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_DETAILS:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_LIST:
                return [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_LIST];

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER:
                return [PoP_Module_Processor_CustomFullUserLayouts::class, PoP_Module_Processor_CustomFullUserLayouts::COMPONENT_LAYOUT_FULLUSER];
        }

        return parent::getDefaultLayoutSubmodule($component);
    }

    public function getMultipleLayoutSubmodules(array $component)
    {
        $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_POPOVER:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR:
                $handles = array(
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_POPOVER => POP_MULTILAYOUT_HANDLE_USERPOPOVER,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_POSTAUTHOR => POP_MULTILAYOUT_HANDLE_USERPOSTAUTHOR,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR => POP_MULTILAYOUT_HANDLE_USERCONTEXTUALPOSTAUTHOR,
                );
                return $multilayout_manager->getLayoutComponents($handles[$component[1]]);

            case self::COMPONENT_LAYOUT_MULTIPLEUSER_NAVIGATOR:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_DETAILS:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_LIST:
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER:
                $formats = array(
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_NAVIGATOR => POP_FORMAT_NAVIGATOR,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS => POP_FORMAT_ADDONS,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_DETAILS => POP_FORMAT_DETAILS,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL => POP_FORMAT_THUMBNAIL,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_LIST => POP_FORMAT_LIST,
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER => POP_FORMAT_FULLVIEW,
                );
                return $multilayout_manager->getLayoutComponents(POP_MULTILAYOUT_HANDLE_USERCONTENT, $formats[$component[1]]);
        }

        return parent::getMultipleLayoutSubmodules($component);
    }
}



