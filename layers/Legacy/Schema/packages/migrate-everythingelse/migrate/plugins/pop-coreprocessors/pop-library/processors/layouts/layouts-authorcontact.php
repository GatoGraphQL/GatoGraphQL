<?php

class PoP_Module_Processor_AuthorContactLayouts extends PoP_Module_Processor_AuthorContactLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTHOR_CONTACT = 'layout-author-contact';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTHOR_CONTACT,
        );
    }
}



