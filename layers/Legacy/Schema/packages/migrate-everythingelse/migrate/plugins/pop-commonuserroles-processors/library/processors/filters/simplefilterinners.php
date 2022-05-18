<?php

class PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS = 'simplefilterinputcontainer-individuals';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS = 'simplefilterinputcontainer-organizations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'CommonUserRoles:SimpleFilterInners:inputmodules',
            $inputmodules[$component[1]],
            $component
        )) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        return $ret;
    }

    // public function getFilter(array $component)
    // {
    //     $filters = array(
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS => POP_FILTER_INDIVIDUALS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS => POP_FILTER_ORGANIZATIONS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



