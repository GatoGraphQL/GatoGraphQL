<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers\Cache;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Engine\DirectiveResolvers\Cache\CacheDirectiveResolverTrait;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;
use PoP\Engine\Cache\CacheTypes;

/**
 * Load the field value from the cache. This directive is executed before `@resolveAndMerge`,
 * and it works together with "@saveCache" (called @cache) which is executed after `@resolveAndMerge`.
 * If @loadCache finds there's a cached value already, then the idsDataFields for directives
 * @resolveAndMerge and @saveCache will be removed, so they have nothing to do
 */
class LoadCacheDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use CacheDirectiveResolverTrait;
    use RemoveIDsDataFieldsDirectiveResolverTrait;

    const DIRECTIVE_NAME = 'loadCache';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     *
     * @return void
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    /**
     * This directive is added automatically by @cache, it's not added by the user
     *
     * @return boolean
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        return true;
    }

    /**
     * Save all the field values into the cache
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $idsDataFields
     * @param array $succeedingPipelineIDsDataFields
     * @param array $resultIDItems
     * @param array $unionDBKeyIDs
     * @param array $dbItems
     * @param array $previousDBItems
     * @param array $variables
     * @param array $messages
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $dbDeprecations
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
        $persistentCache = PersistentCacheFacade::getInstance();
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $idsDataFieldsToRemove = [];
        $cacheType = CacheTypes::CACHE_DIRECTIVE;
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $cacheID = $this->getCacheID($typeResolver, $id, $field);
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                if ($persistentCache->hasCache($cacheID, $cacheType)) {
                    $dbItems[(string)$id][$fieldOutputKey] = $persistentCache->getCache($cacheID, $cacheType);
                    $idsDataFieldsToRemove[(string)$id]['direct'][] = $field;
                }
            }
        }
        /**
         * Remove the IDs from all directives until @cache, which need not be applied since their output is part of the cache
         * This includes directives @resolveAndMerge and @cache, and others in between such as @translate
         * Must check that directives which do not apply on the $resultIDItems, such as @cacheControl, are not affected
         * (check function `needsIDsDataFieldsToExecute` must be `false` for them)
         */
        if ($idsDataFieldsToRemove) {
            // Find the position of the @cache directive. Compare by name and not by class, just in case the directive class was overriden
            $pos = 0;
            $found = false;
            while (!$found && $pos < count($succeedingPipelineDirectiveResolverInstances)) {
                $directiveResolverInstance = $succeedingPipelineDirectiveResolverInstances[$pos];
                if ($directiveResolverInstance->getDirectiveName() == SaveCacheDirectiveResolver::getDirectiveName()) {
                    $found = true;
                } else {
                    $pos++;
                }
            }
            if ($found) {
                // Create a subsection array, containing all elements until $pos, by reference
                // (so the changes applied to this array are also applied on the original one)
                $pipelineIDsDataFieldsToRemove = [];
                for ($i = 0; $i <= $pos; $i++) {
                    $pipelineIDsDataFieldsToRemove[] = &$succeedingPipelineIDsDataFields[$i];
                }

                // Remove the $idsDataFields for them
                $this->removeIDsDataFields($idsDataFieldsToRemove, $pipelineIDsDataFieldsToRemove);
            }

            // Log the cached items
            $feedbackMessageStore = FeedbackMessageStoreFacade::getInstance();
            $translationAPI = TranslationAPIFacade::getInstance();
            $logCachedIDFields = [];
            foreach ($idsDataFieldsToRemove as $id => $dataFieldsToRemove) {
                $logCachedIDFields[] = sprintf(
                    $translationAPI->__('ID: %s, Field(s): \'%s\'', 'engine'),
                    $id,
                    implode('\', \'', $dataFieldsToRemove['direct'])
                );
            }
            $feedbackMessageStore->addLogEntry(
                sprintf(
                    $translationAPI->__('The following fields of type \'%s\' were resolved from the cache - %s', 'engine'),
                    $typeResolver->getTypeName(),
                    implode(
                        $translationAPI->__('; ', 'engine'),
                        $logCachedIDFields
                    )
                )
            );
        }
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Load the cached value for a field', 'engine');
    }
}
