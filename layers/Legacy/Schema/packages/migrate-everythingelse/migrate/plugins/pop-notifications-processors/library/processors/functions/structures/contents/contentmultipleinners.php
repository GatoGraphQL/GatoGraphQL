<?php

class GD_AAL_Module_Processor_FunctionsContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD = 'contentinner-marknotificationasread';
    public final const COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD = 'contentinner-marknotificationasunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD],
            [self::class, self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES];

                // Allow to add extra styles, such as changing background color, etc
                if ($extra = \PoP\Root\App::applyFilters(
                    'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasread:layouts',
                    array(),
                    $component
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $extra
                    );
                }
                break;

            case self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD:
                $ret[] = [GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts::class, GD_AAL_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES];

                // Allow to add extra styles, such as changing background color, etc
                if ($extra = \PoP\Root\App::applyFilters(
                    'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasunread:layouts',
                    array(),
                    $component
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $extra
                    );
                }
                break;
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD:
            case self::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD:
                // In addition, bring the new status (read/unread) of the notification to update the database/userstatedatabase in the webplatform, for consistency
                $ret[] = 'status';
                break;
        }

        return $ret;
    }
}


