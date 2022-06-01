<?php
use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class PoP_Module_Processor_UserPlatformFilterInput extends AbstractValueToQueryFilterInput
{
    public final const FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public final const FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public final const FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    /**
     * @todo Split this class into multiple ones, returning a single string per each ($filterInput is not valid anymore)
     */
    protected function getQueryArgKey(): string
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
                    $elements = explode('|', $pair);
                    $taxonomy = $elements[0];
                    $term = $elements[1];
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
