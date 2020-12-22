<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

use PoP\CacheControl\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\CacheControl\Facades\CacheControlEngineFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

abstract class AbstractCacheControlDirectiveResolver extends AbstractGlobalDirectiveResolver implements CacheControlDirectiveResolverInterface
{
    const DIRECTIVE_NAME = 'cacheControl';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    /**
     * Set the cache even when there are no elements: they might've been removed due to some validation, and this caching maxAge must be respected!
     *
     * @return boolean
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        return false;
    }

    /**
     * Because this directive will be implemented several times, make its schema definition be added only once
     *
     * @return void
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        return true;
    }

    /**
     * This is a "Schema" type directive
     *
     * @return string
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCHEMA;
    }

    /**
     * Allow it to execute multiple times
     *
     * @return boolean
     */
    public function isRepeatable(): bool
    {
        return true;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('HTTP caching (https://tools.ietf.org/html/rfc7234): Cache the response by setting a Cache-Control header with a max-age value; this value is calculated as the minimum max-age value among all requested fields. If any field has max-age: 0, a corresponding \'no-store\' value is sent, indicating to not cache the response', 'component-model');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'maxAge',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INT,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Use a specific max-age value for the field, instead of the one configured in the directive', 'translate-directive'),
            ],
        ];
    }

    // protected function addSchemaDefinitionForDirective(array &$schemaDefinition)
    // {
    //     // Further add for which providers it works
    //     $maxAge = $this->getMaxAge();
    //     if (!is_null($maxAge)) {
    //         $schemaDefinition[SchemaDefinition::ARGNAME_MAX_AGE] = $maxAge;
    //     }
    // }

    /**
     * Do not allow dynamic fields, or it may throw an exception
     * Eg: <cacheControl(maxAge:id())>
     *
     * @return bool
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    /**
     * Get the cache control for this field, and set it on the Engine
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $resultIDItems
     * @param array $idsDataFields
     * @param array $dbItems
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return void
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
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
            $cacheControlEngine = CacheControlEngineFacade::getInstance();
            $cacheControlEngine->addMaxAge($maxAge);
        }
    }

    abstract public function getMaxAge(): ?int;
}
