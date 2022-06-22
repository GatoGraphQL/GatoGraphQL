<?php

class PoP_UserCommunities_EM_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_COMMUNITIES_MAP = 'scrollinner-communities-map';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_COMMUNITIES_MAP,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_COMMUNITIES_MAP:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_COMMUNITIES_MAP => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


