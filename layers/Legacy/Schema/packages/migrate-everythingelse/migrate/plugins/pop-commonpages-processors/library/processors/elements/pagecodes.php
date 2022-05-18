<?php

class GD_CommonPages_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const MODULE_PAGECODE_ADDCONTENTFAQ = 'pagecode-addcontentfaq';
    public final const MODULE_PAGECODE_ACCOUNTFAQ = 'pagecode-accountfaq';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGECODE_ADDCONTENTFAQ],
            [self::class, self::MODULE_PAGECODE_ACCOUNTFAQ],
        );
    }

    public function getPageId(array $module)
    {
        $page_ids = array(
            self::MODULE_PAGECODE_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
            self::MODULE_PAGECODE_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
        );
        if ($page_id = $page_ids[$module[1]] ?? null) {
            return $page_id;
        }

        return parent::getPageId($module);
    }
}


