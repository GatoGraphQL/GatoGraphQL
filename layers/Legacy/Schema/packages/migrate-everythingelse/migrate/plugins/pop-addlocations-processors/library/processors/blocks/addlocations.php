<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
class GD_EM_Module_Processor_CreateLocationBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_CREATELOCATION = 'block-createlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CREATELOCATION],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION];
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                // Add an extra modulepath to the dataload-source for the create-location block, saying to also load the data block
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $submodule_propagation_path = $module_path_manager->getPropagationCurrentPath();
                $submodule_propagation_path[] = $module;
                $submodule_propagation_path[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                $this->mergeProp(
                    [
                        [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION]
                    ],
                    $props,
                    'dataload-source-add-modulepaths',
                    array(
                        $submodule_propagation_path,
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}



