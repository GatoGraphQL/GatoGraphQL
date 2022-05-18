<?php

class PoP_Module_Processor_CommentCardLayouts extends PoP_Module_Processor_CommentCardLayoutsBase
{
    public final const MODULE_LAYOUTCOMMENT_CARD = 'layoutcomment-card';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTCOMMENT_CARD],
        );
    }
}



