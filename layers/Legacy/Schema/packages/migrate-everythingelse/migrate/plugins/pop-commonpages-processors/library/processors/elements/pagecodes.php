<?php

class GD_CommonPages_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const COMPONENT_PAGECODE_ADDCONTENTFAQ = 'pagecode-addcontentfaq';
    public final const COMPONENT_PAGECODE_ACCOUNTFAQ = 'pagecode-accountfaq';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_PAGECODE_ADDCONTENTFAQ,
            self::COMPONENT_PAGECODE_ACCOUNTFAQ,
        );
    }

    public function getPageID(\PoP\ComponentModel\Component\Component $component)
    {
        $page_ids = array(
            self::COMPONENT_PAGECODE_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
            self::COMPONENT_PAGECODE_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
        );
        if ($page_id = $page_ids[$component->name] ?? null) {
            return $page_id;
        }

        return parent::getPageID($component);
    }
}


