<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\InputObjectType;

use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostDateQueryInputObjectTypeResolver as UpstreamCustomPostDateQueryInputObjectTypeResolver;
use stdClass;

class CustomPostDateQueryInputObjectTypeResolver extends UpstreamCustomPostDateQueryInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

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
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    /**
     * Integrate parameters into the "date_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_query/#date-parameters
     *
     * @param array<string, mixed> $query
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass $inputValue): void
    {
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
