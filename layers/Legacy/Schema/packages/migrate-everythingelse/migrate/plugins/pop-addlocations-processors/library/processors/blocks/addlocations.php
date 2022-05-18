<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
class GD_EM_Module_Processor_CreateLocationBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_CREATELOCATION = 'block-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CREATELOCATION],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION];
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                // Add an extra componentPath to the dataload-source for the create-location block, saying to also load the data block
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $submodule_propagation_path = $module_path_manager->getPropagationCurrentPath();
                $submodule_propagation_path[] = $component;
                $submodule_propagation_path[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                $this->mergeProp(
                    [
                        [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION]
                    ],
                    $props,
                    'dataload-source-add-componentPaths',
                    array(
                        $submodule_propagation_path,
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}



