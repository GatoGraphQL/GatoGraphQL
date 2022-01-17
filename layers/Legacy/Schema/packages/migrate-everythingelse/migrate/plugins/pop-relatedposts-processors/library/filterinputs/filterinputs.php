<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class PoP_Module_Processor_ReferencesFilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponent-selectabletypeahead-references';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
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
