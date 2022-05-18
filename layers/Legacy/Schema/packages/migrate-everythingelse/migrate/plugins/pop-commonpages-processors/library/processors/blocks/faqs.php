<?php

class GD_CommonPages_Module_Processor_CustomBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_ADDCONTENTFAQ = 'block-addcontentfaq';
    public final const MODULE_BLOCK_ACCOUNTFAQ = 'block-accountfaq';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_ADDCONTENTFAQ],
            [self::class, self::MODULE_BLOCK_ACCOUNTFAQ],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
            self::MODULE_BLOCK_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_ADDCONTENTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_PageCodes::class, GD_CommonPages_Module_Processor_PageCodes::MODULE_PAGECODE_ADDCONTENTFAQ];
                break;

            case self::MODULE_BLOCK_ACCOUNTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_PageCodes::class, GD_CommonPages_Module_Processor_PageCodes::MODULE_PAGECODE_ACCOUNTFAQ];
                break;
        }

        return $ret;
    }
}



