<?php

class GD_EM_Module_Processor_LocationTypeaheadsSelectedLayouts extends GD_EM_Module_Processor_LocationTypeaheadsSelectedLayoutsBase
{
    public final const COMPONENT_LAYOUTLOCATION_CARD = 'em-layoutlocation-card';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTLOCATION_CARD,
        );
    }
}



