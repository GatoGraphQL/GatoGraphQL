<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\InputObjectType;

use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostDateQueryInputObjectTypeResolver as UpstreamCustomPostDateQueryInputObjectTypeResolver;
use stdClass;

class CustomPostDateQueryInputObjectTypeResolver extends UpstreamCustomPostDateQueryInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'inclusive' => $this->getBooleanScalarTypeResolver(),
                'year' => $this->getIntScalarTypeResolver(),
                'month' => $this->getIntScalarTypeResolver(),
                'week' => $this->getIntScalarTypeResolver(),
                'day' => $this->getIntScalarTypeResolver(),
                'hour' => $this->getIntScalarTypeResolver(),
                'minute' => $this->getIntScalarTypeResolver(),
                'second' => $this->getIntScalarTypeResolver(),
                'compare' => $this->getStringScalarTypeResolver(),
                'column' => $this->getStringScalarTypeResolver(),
                'relation' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'inclusive' => $this->getTranslationAPI()->__('For after/before, whether exact value should be matched or not', 'customposts'),
            'year' => $this->getTranslationAPI()->__('4 digit year (e.g. 2011)', 'customposts'),
            'month' => $this->getTranslationAPI()->__('Month number (from 1 to 12)', 'customposts'),
            'week' => $this->getTranslationAPI()->__('Week of the year (from 0 to 53)', 'customposts'),
            'day' => $this->getTranslationAPI()->__('Day of the month (from 1 to 31)', 'customposts'),
            'hour' => $this->getTranslationAPI()->__('Hour (from 0 to 23)', 'customposts'),
            'minute' => $this->getTranslationAPI()->__('Minute (from 0 to 59)', 'customposts'),
            'second' => $this->getTranslationAPI()->__('Second (0 to 59)', 'customposts'),
            'compare' => $this->getTranslationAPI()->__('Determines and validates what comparison operator to use', 'customposts'),
            'column' => $this->getTranslationAPI()->__('Posts column to query against. Default: ‘post_date’)', 'customposts'),
            'relation' => $this->getTranslationAPI()->__('OR or AND, how the sub-arrays should be compared. Default: AND', 'customposts'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    /**
     * Integrate parameters into the "date_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_query/#date-parameters
     *
     * @param array<string, mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        if (is_array($inputValue)) {
            $innerQueries = [];
            parent::integrateInputValueToFilteringQueryArgs($innerQueries, $inputValue);
            $query['date_query'] = [];
            // The "relation" is defined on the first element
            if (isset($innerQueries[0]['date_query']['relation'])) {
                $query['date_query']['relation'] = $innerQueries[0]['date_query']['relation'];
            }
            foreach ($innerQueries as $innerQuery) {
                $query['date_query'][] = $innerQuery['date_query'];
            }
            return;
        }

        $dateQuery = [];

        if (isset($inputValue->before)) {
            $dateQuery['before'] = $this->getDateScalarTypeResolver()->serialize($inputValue->before);
        }
        if (isset($inputValue->after)) {
            $dateQuery['after'] = $this->getDateScalarTypeResolver()->serialize($inputValue->after);
        }
        
        $properties = [
            'year',
            'month',
            'week',
            'day',
            'hour',
            'minute',
            'second',
            'inclusive',
            'compare',
            'column',
            'relation',
        ];
        foreach ($properties as $property) {
            if (isset($inputValue->$property)) {
                $dateQuery[$property] = $inputValue->$property;
            }
        }

        if ($dateQuery !== []) {
            $query['date_query'] = $dateQuery;
        }
    }
}
