<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use stdClass;

class CustomPostDateQueryInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostDateQueryInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'after' => $this->getDateScalarTypeResolver(),
            'before' => $this->getDateScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'after' => $this->getTranslationAPI()->__('Retrieve custom posts from after this date', 'schema-commons'),
            'before' => $this->getTranslationAPI()->__('Retrieve custom posts from before this date', 'schema-commons'),
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
        if (isset($inputValue->before)) {
            $query['date-to'] = $this->getDateScalarTypeResolver()->serialize($inputValue->before);
        }
        if (isset($inputValue->after)) {
            $query['date-from'] = $this->getDateScalarTypeResolver()->serialize($inputValue->after);
        }
    }
}
