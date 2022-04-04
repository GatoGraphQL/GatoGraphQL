<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class PoP_Module_Processor_UserStanceFilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_STANCE_MULTISELECT = 'filterinput-multiselect-stance';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_STANCE_MULTISELECT],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {

            case self::FILTERINPUT_STANCE_MULTISELECT:
                $query['tax-query'] = $query['tax-query'] ?? ['relation' => 'AND'];
                $taxonomy_terms = [];
                foreach ($value as $pair) {
                    $component = explode('|', $pair);
                    $taxonomy = $component[0];
                    $term = $component[1];
                    $taxonomy_terms[$taxonomy][] = $term;
                }
                foreach ($taxonomy_terms as $taxonomy => $terms) {
                    $query['tax-query'][] = [
                        'taxonomy' => $taxonomy,
                        'terms' => $terms,
                    ];
                }
                break;
        }
    }
}
