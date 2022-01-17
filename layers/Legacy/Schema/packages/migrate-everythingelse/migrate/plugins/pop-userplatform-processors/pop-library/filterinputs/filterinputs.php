<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class PoP_Module_Processor_UserPlatformFilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public const FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public const FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_BUTTONGROUP_CATEGORIES:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_CATEGORIES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
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

            case self::FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                $query['categories'] = $value;
                break;
        }
    }
}
