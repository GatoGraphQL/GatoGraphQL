<?php
use PoP\Application\Constants\Actions;
use PoP\ComponentModel\State\ApplicationState;

class Wassup_Module_Processor_MultipleComponentLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION = 'multicomponent-userhighlightpostinteraction';
    public final const MODULE_MULTICOMPONENT_USERPOSTINTERACTION = 'multicomponent-userpostinteraction';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION],
            [self::class, self::MODULE_MULTICOMPONENT_USERPOSTINTERACTION],
        );
    }

    protected function getUserpostinteractionLayoutSubmodules(array $componentVariation)
    {
        $loadingLazy = in_array(Actions::LOADLAZY, \PoP\Root\App::getState('actions'));
        switch ($componentVariation[1]) {
         // Highlights: it has a different set-up
            case self::MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
                $layouts = array(
                    [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION],
                );
                if ($loadingLazy) {
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                }
                break;

            case self::MODULE_MULTICOMPONENT_USERPOSTINTERACTION:
                $layouts = array(
                    [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION],
                );
                if ($loadingLazy) {
                    $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_SUBCOMPONENT_HIGHLIGHTS];
                    $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
                    $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
                    $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS];
                }
                break;
        }

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters(
            'Wassup_Module_Processor_MultipleComponentLayouts:userpostinteraction_layouts',
            $layouts,
            $componentVariation
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
            case self::MODULE_MULTICOMPONENT_USERPOSTINTERACTION:
                $ret = array_merge(
                    $ret,
                    $this->getUserpostinteractionLayoutSubmodules($componentVariation)
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
            case self::MODULE_MULTICOMPONENT_USERPOSTINTERACTION:
                $this->appendProp($componentVariation, $props, 'class', 'userpostinteraction-single');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



