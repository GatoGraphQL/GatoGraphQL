<?php

class PoP_Module_Processor_CommentCardLayouts extends PoP_Module_Processor_CommentCardLayoutsBase
{
    public const MODULE_LAYOUTCOMMENT_CARD = 'layoutcomment-card';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTCOMMENT_CARD],
        );
    }
}



