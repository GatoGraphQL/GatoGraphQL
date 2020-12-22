<?php
class PoP_Module_Processor_ReferencesFilterInputProcessor extends \PoP\ComponentModel\AbstractFilterInputProcessor
{
    public const FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponent-selectabletypeahead-references';

    public function getFilterInputsToProcess()
    {
        return array(
            [self::class, self::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value)
    {
        switch ($filterInput[1]) {

            case self::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                $query['meta-query'][] = [
                    'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_REFERENCES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}
