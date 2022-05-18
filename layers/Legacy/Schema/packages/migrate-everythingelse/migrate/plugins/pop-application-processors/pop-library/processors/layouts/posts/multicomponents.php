<?php
use PoP\Application\Constants\Actions;
use PoP\ComponentModel\State\ApplicationState;

class Wassup_Module_Processor_MultipleComponentLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION = 'multicomponent-userhighlightpostinteraction';
    public final const MODULE_MULTICOMPONENT_USERPOSTINTERACTION = 'multicomponent-userpostinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION],
            [self::class, self::COMPONENT_MULTICOMPONENT_USERPOSTINTERACTION],
        );
    }

    protected function getUserpostinteractionLayoutSubmodules(array $component)
    {
        $loadingLazy = in_array(Actions::LOADLAZY, \PoP\Root\App::getState('actions'));
        switch ($component[1]) {
         // Highlights: it has a different set-up
            case self::COMPONENT_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
                $layouts = array(
                    [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION],
                );
                if ($loadingLazy) {
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_USERPOSTINTERACTION:
                $layouts = array(
                    [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION],
                );
                if ($loadingLazy) {
                    $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_SUBCOMPONENT_HIGHLIGHTS];
                    $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
                    $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS];
                }
                break;
        }

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters(
            'Wassup_Module_Processor_MultipleComponentLayouts:userpostinteraction_layouts',
            $layouts,
            $component
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
            case self::COMPONENT_MULTICOMPONENT_USERPOSTINTERACTION:
                $ret = array_merge(
                    $ret,
                    $this->getUserpostinteractionLayoutSubmodules($component)
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
            case self::COMPONENT_MULTICOMPONENT_USERPOSTINTERACTION:
                $this->appendProp($component, $props, 'class', 'userpostinteraction-single');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



