<?php

class PoPTheme_Wassup_AE_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS = 'scrollinner-automatedemails-latestcontent-details';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW = 'scrollinner-automatedemails-latestcontent-simpleview';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW = 'scrollinner-automatedemails-latestcontent-fullview';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL = 'scrollinner-automatedemails-latestcontent-thumbnail';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST = 'scrollinner-automatedemails-latestcontent-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_DETAILS],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_LIST],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::class, PoPTheme_Wassup_AE_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_MULTIPLECONTENT_FULLVIEW],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW:
                // Add an extra space at the bottom of each post
                $this->appendProp($component, $props, 'class', 'email-scrollelem-post');
        }

        parent::initModelProps($component, $props);
    }
}


