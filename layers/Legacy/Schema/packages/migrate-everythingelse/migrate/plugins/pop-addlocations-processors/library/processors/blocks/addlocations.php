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

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION];
                $ret[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CREATELOCATION:
                // Add an extra componentVariationPath to the dataload-source for the create-location block, saying to also load the data block
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $submodule_propagation_path = $module_path_manager->getPropagationCurrentPath();
                $submodule_propagation_path[] = $componentVariation;
                $submodule_propagation_path[] = [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION];
                $this->mergeProp(
                    [
                        [GD_EM_Module_Processor_CreateLocationDataloads::class, GD_EM_Module_Processor_CreateLocationDataloads::MODULE_DATALOAD_CREATELOCATION]
                    ],
                    $props,
                    'dataload-source-add-componentVariationPaths',
                    array(
                        $submodule_propagation_path,
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



