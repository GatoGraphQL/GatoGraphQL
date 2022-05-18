<?php

class GD_CommonPages_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const MODULE_PAGECODE_ADDCONTENTFAQ = 'pagecode-addcontentfaq';
    public final const MODULE_PAGECODE_ACCOUNTFAQ = 'pagecode-accountfaq';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGECODE_ADDCONTENTFAQ],
            [self::class, self::COMPONENT_PAGECODE_ACCOUNTFAQ],
        );
    }

    public function getPageId(array $component)
    {
        $page_ids = array(
            self::COMPONENT_PAGECODE_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
            self::COMPONENT_PAGECODE_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
        );
        if ($page_id = $page_ids[$component[1]] ?? null) {
            return $page_id;
        }

        return parent::getPageId($component);
    }
}


