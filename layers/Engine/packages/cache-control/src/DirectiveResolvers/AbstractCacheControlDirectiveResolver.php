<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCacheControlDirectiveResolver extends AbstractGlobalDirectiveResolver implements CacheControlDirectiveResolverInterface
{
    protected CacheControlEngineInterface $cacheControlEngine;
    protected IntScalarTypeResolver $intScalarTypeResolver;

    #[Required]
    final public function autowireAbstractCacheControlDirectiveResolver(
        CacheControlEngineInterface $cacheControlEngine,
        IntScalarTypeResolver $intScalarTypeResolver,
    ): void {
        $this->cacheControlEngine = $cacheControlEngine;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }

    public function getDirectiveName(): string
    {
        return 'cacheControl';
    }

    /**
     * Set the cache even when there are no elements: they might've been removed due to some validation, and this caching maxAge must be respected!
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        return false;
    }

    /**
     * Because this directive will be implemented several times, make its schema definition be added only once
     */
    public function skipAddingToSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }

    /**
     * This is a "Schema" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCHEMA;
    }

    /**
     * Allow it to execute multiple times
     */
    public function isRepeatable(): bool
    {
        return true;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->translationAPI->__('HTTP caching (https://tools.ietf.org/html/rfc7234): Cache the response by setting a Cache-Control header with a max-age value; this value is calculated as the minimum max-age value among all requested fields. If any field has max-age: 0, a corresponding \'no-store\' value is sent, indicating to not cache the response', 'cache-control');
    }
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'maxAge' => $this->intScalarTypeResolver,
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'maxAge' => $this->translationAPI->__('Use a specific max-age value for the field, instead of the one configured in the directive', 'cache-control'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    /**
     * Validate the constraints for a directive argument
     *
     * @return string[] Error messages
     */
    protected function validateDirectiveArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        string $directiveArgName,
        mixed $directiveArgValue
    ): array {
        $errors = parent::validateDirectiveArgument(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgName,
            $directiveArgValue,
        );

        switch ($directiveArgName) {
            case 'maxAge':
                if ($directiveArgValue < 0) {
                    $errors[] = $this->translationAPI->__('The value for \'maxAge\' must either be a positive number, or \'0\' to avoid caching');
                }
                break;
        }
        return $errors;
    }

    // protected function addDirectiveSchemaDefinition(array &$schemaDefinition): void
    // {
    //     // Further add for which providers it works
    //     $maxAge = $this->getMaxAge();
    //     if (!is_null($maxAge)) {
    //         $schemaDefinition[SchemaDefinition::MAX_AGE] = $maxAge;
    //     }
    // }
    /**
     * Do not allow dynamic fields, or it may throw an exception
     * Eg: <cacheControl(maxAge:id())>
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    /**
     * Get the cache control for this field, and set it on the Engine
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $this->resolveCacheControlDirective();
    }

    public function resolveCacheControlDirective(): void
    {
        // Set the max age from this field into the service which will calculate the max age for the request, based on all fields
        // If it was provided as a directiveArg, use that value. Otherwise, use the one from the class
        $maxAge = $this->directiveArgsForSchema['maxAge'] ?? $this->getMaxAge();
        if (!is_null($maxAge)) {
            $this->cacheControlEngine->addMaxAge($maxAge);
        }
    }

    abstract public function getMaxAge(): ?int;
}
