<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers;

use PoP\Engine\Cache\CacheTypes;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

/**
 * Save the field value into the cache. This directive is executed after `@resolveAndMerge`,
 * and it works together with "@loadCache" which is executed before `@resolveAndMerge`.
 * If @loadCache finds there's a cached value already, then the idsDataFields for directives
 * @resolveAndMerge and this @saveCache (called @cache) will be removed, so they have nothing to do
 *
 * Watch out! When caching, all directives on the way to @cache may be cached too!
 * Then, executing these queries has different results:
 *
 * 1. { siteName @cache @translate }
 * 2. { siteName @translate @cache }
 *
 * In the 1st case, the result from @translate is not added to the cache, and @translate will be executed always
 * In the 2nd case, the result from @translate is added to the cache, and won't be executed again
 *
 * However, the directives before @cache are executed, but they are not passed any $idsDataFields to execute upon
 * Hence, directives where `needsIDsDataFieldsToExecute` is false should not be affected (eg: @cacheControl)
 */
class SaveCacheDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use CacheDirectiveResolverTrait;

    /**
     * It's called "cache" instead of "saveCache" because it's more user-friendly,
     * and because "cache" involves both "loadCache" and "saveCache", where "loadCache"
     * is added as a mandatory directive on directive
     */
    const DIRECTIVE_NAME = 'cache';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
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
        $translationAPI = TranslationAPIFacade::getInstance();
        $cacheType = CacheTypes::CACHE_DIRECTIVE;
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $cacheID = $this->getCacheID($typeResolver, $id, $field);
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                if (!array_key_exists($fieldOutputKey, $dbItems[(string)$id])) {
                    $dbWarnings[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $translationAPI->__('Property \'%s\' doesn\'t exist in object with ID \'%s\', so it can\'t be cached'),
                            $fieldOutputKey,
                            $id
                        ),
                    ];
                    continue;
                }
                $persistentCache->storeCache(
                    $cacheID,
                    $cacheType,
                    $dbItems[(string)$id][$fieldOutputKey],
                    $this->directiveArgsForSchema['time']
                );
            }
        }
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Cache the field value, and retrive from the cache if available', 'engine');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'time',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INT,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Amount of time, in seconds, that the cache is valid. If not defining this value, the cache has no expiry date', 'engine'),
            ],
        ];
    }
}
