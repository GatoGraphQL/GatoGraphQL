<?php

class PoP_Module_Processor_CommentCardLayouts extends PoP_Module_Processor_CommentCardLayoutsBase
{
    public final const COMPONENT_LAYOUTCOMMENT_CARD = 'layoutcomment-card';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTCOMMENT_CARD,
        );
    }
}



