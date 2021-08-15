<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnContext\UseComponentModelCache\SchemaServices\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
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

    public function getDirectiveName(): string
    {
        return 'loadCache';
    }

    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    /**
     * This directive is added automatically by @cache, it's not added by the user
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        return true;
    }

    /**
     * Save all the field values into the cache
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
        $idsDataFieldsToRemove = [];
        $cacheType = CacheTypes::CACHE_DIRECTIVE;
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $cacheID = $this->getCacheID($typeResolver, $id, $field);
                $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($typeResolver, $field);
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
            /** @var DirectiveResolverInterface */
            $saveCacheDirectiveResolver = $this->instanceManager->getInstance(SaveCacheDirectiveResolver::class);
            $directiveName = $saveCacheDirectiveResolver->getDirectiveName();
            while (!$found && $pos < count($succeedingPipelineDirectiveResolverInstances)) {
                $directiveResolverInstance = $succeedingPipelineDirectiveResolverInstances[$pos];
                if ($directiveResolverInstance->getDirectiveName() == $directiveName) {
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
                $this->removeIDsDataFields(
                    $idsDataFieldsToRemove,
                    $pipelineIDsDataFieldsToRemove
                );
            }

            // Log the cached items
            $logCachedIDFields = [];
            foreach ($idsDataFieldsToRemove as $id => $dataFieldsToRemove) {
                $logCachedIDFields[] = sprintf(
                    $this->translationAPI->__('ID: %s, Field(s): \'%s\'', 'engine'),
                    $id,
                    implode('\', \'', $dataFieldsToRemove['direct'])
                );
            }
            $this->feedbackMessageStore->addLogEntry(
                sprintf(
                    $this->translationAPI->__('The following fields of type \'%s\' were resolved from the cache - %s', 'engine'),
                    $typeResolver->getTypeName(),
                    implode(
                        $this->translationAPI->__('; ', 'engine'),
                        $logCachedIDFields
                    )
                )
            );
        }
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('Load the cached value for a field', 'engine');
    }
}
