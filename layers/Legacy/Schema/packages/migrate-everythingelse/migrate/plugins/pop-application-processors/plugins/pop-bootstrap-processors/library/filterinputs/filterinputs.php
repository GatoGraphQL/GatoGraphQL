<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_APPLIESTO = 'filterinput-appliesto';
    public final const FILTERINPUT_CATEGORIES = 'filterinput-categories';
    public final const FILTERINPUT_CONTENTSECTIONS = 'filterinput-contentsections';
    public final const FILTERINPUT_POSTSECTIONS = 'filterinput-postsections';
    public final const FILTERINPUT_VOLUNTEERSNEEDED = 'filterinput-volunteersneeded';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_APPLIESTO],
            [self::class, self::FILTERINPUT_CATEGORIES],
            [self::class, self::FILTERINPUT_CONTENTSECTIONS],
            [self::class, self::FILTERINPUT_POSTSECTIONS],
            [self::class, self::FILTERINPUT_VOLUNTEERSNEEDED],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_POSTSECTIONS:
                $query['categories'] = $value;
                break;

            case self::FILTERINPUT_CATEGORIES:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_CATEGORIES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::FILTERINPUT_CONTENTSECTIONS:
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

            case self::FILTERINPUT_APPLIESTO:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_APPLIESTO),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::FILTERINPUT_VOLUNTEERSNEEDED:

                $compare = 'EXISTS';
                // if (is_array($value)) {
                // Do the filtering only if there is 1 value (2 values => same as not filtering)
                if (count($value) !== 1) {
                    return;
                }

                // Only 1 value in the multiselect, extract it
                $value = $value[0];
                // }

                // Do the filtering: if $value is false, then filter by NOT EXISTS
                if ($value === false) {
                    $value = true;
                    $compare = 'NOT EXISTS';
                }

                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_VOLUNTEERSNEEDED),
                    'value' => $value,
                    'compare' => $compare,
                ];
                break;
        }
    }
}



