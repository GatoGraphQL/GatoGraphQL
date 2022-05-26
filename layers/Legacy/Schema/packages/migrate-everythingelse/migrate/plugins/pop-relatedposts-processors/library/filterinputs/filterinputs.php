<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class PoP_Module_Processor_ReferencesFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    public final const FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponent-selectabletypeahead-references';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    /**
     * @todo Split this class into multiple ones, returning a single string per each ($filterInput is not valid anymore)
     */
    protected function getQueryArgKey(): string
    {
        switch ($filterInput[1]) {

            case self::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_REFERENCES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}
