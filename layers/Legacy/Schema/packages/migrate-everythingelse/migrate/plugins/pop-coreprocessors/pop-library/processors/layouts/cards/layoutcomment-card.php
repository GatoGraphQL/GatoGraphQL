<?php

class PoP_Module_Processor_CommentCardLayouts extends PoP_Module_Processor_CommentCardLayoutsBase
{
    public final const COMPONENT_LAYOUTCOMMENT_CARD = 'layoutcomment-card';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTCOMMENT_CARD],
        );
    }
}



