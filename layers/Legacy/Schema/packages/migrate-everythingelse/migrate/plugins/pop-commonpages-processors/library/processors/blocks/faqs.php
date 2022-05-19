<?php

class GD_CommonPages_Module_Processor_CustomBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_ADDCONTENTFAQ = 'block-addcontentfaq';
    public final const COMPONENT_BLOCK_ACCOUNTFAQ = 'block-accountfaq';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_ADDCONTENTFAQ],
            [self::class, self::COMPONENT_BLOCK_ACCOUNTFAQ],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
            self::COMPONENT_BLOCK_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_ADDCONTENTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_PageCodes::class, GD_CommonPages_Module_Processor_PageCodes::COMPONENT_PAGECODE_ADDCONTENTFAQ];
                break;

            case self::COMPONENT_BLOCK_ACCOUNTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_PageCodes::class, GD_CommonPages_Module_Processor_PageCodes::COMPONENT_PAGECODE_ACCOUNTFAQ];
                break;
        }

        return $ret;
    }
}



