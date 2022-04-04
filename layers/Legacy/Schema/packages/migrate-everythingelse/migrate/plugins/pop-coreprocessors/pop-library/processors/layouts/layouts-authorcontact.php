<?php

class PoP_Module_Processor_AuthorContactLayouts extends PoP_Module_Processor_AuthorContactLayoutsBase
{
    public final const MODULE_LAYOUT_AUTHOR_CONTACT = 'layout-author-contact';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTHOR_CONTACT],
        );
    }
}



