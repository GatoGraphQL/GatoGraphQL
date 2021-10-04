<?php

declare(strict_types=1);

namespace PoP\API\Registries;

use PoP\API\Cache\CacheTypes;
use PoP\API\ComponentConfiguration;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Engine\Cache\CacheUtils;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionRegistry implements SchemaDefinitionRegistryInterface
{
    protected PersistentCacheInterface $persistentCache;
    protected FeedbackMessageStoreInterface $feedbackMessageStore;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;
    protected TranslationAPIInterface $translationAPI;
    protected InstanceManagerInterface $instanceManager;
    protected RootObjectTypeResolver $rootTypeResolver;
    protected Root $root;

    #[Required]
    final public function autowireSchemaDefinitionRegistry(
        FeedbackMessageStoreInterface $feedbackMessageStore,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        PersistentCacheInterface $persistentCache,
        RootObjectTypeResolver $rootTypeResolver,
        Root $root,
    ): void {
        $this->feedbackMessageStore = $feedbackMessageStore;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        $this->translationAPI = $translationAPI;
        $this->instanceManager = $instanceManager;
        $this->persistentCache = $persistentCache;
        $this->rootTypeResolver = $rootTypeResolver;
        $this->root = $root;
    }

    /**
     * @var array<string, array>
     */
    protected array $schemaInstances = [];

    /**
     * Create a key from the arrays, to cache the results
     *
     * @param array<string, mixed> $fieldArgs
     */
    protected function getArgumentKey(?array $fieldArgs, ?array $options): string
    {
        return json_encode($fieldArgs ?? []) . json_encode($options ?? []);
    }

    /**
     * Produce the schema definition. It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     *
     * Return null if retrieving the schema data via field "fullSchema" failed
     */
    public function &getSchemaDefinition(?array $fieldArgs = [], ?array $options = []): ?array
    {
        // Create a key from the arrays, to cache the results
        $key = $this->getArgumentKey($fieldArgs, $options);
        // Watch out: the result can be null!
        if (!array_key_exists($key, $this->schemaInstances)) {
            // Attempt to retrieve from the cache, if enabled
            if ($useCache = ComponentConfiguration::useSchemaDefinitionCache()) {
                // Use different caches for the normal and namespaced schemas,  or
                // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                $cacheType = CacheTypes::SCHEMA_DEFINITION;
                $cacheKeyComponents = CacheUtils::getSchemaCacheKeyComponents();
                // For the persistentCache, use a hash to remove invalid characters (such as "()")
                $cacheKey = hash('md5', $key . '|' . json_encode($cacheKeyComponents));
            }
            $schemaDefinition = null;
            if ($useCache) {
                if ($this->persistentCache->hasCache($cacheKey, $cacheType)) {
                    $schemaDefinition = $this->persistentCache->getCache($cacheKey, $cacheType);
                }
            }
            // If either not using cache, or using but the value had not been cached, then calculate the value
            if ($schemaDefinition === null) {
                $schemaDefinition = $this->rootTypeResolver->resolveValue(
                    $this->root,
                    $this->fieldQueryInterpreter->getField('fullSchema', $fieldArgs ?? []),
                    null,
                    null,
                    $options
                );

                if (GeneralUtils::isError($schemaDefinition)) {
                    /** @var Error */
                    $error = $schemaDefinition;
                    // Store the error, and reset the definition to empty
                    $this->feedbackMessageStore->addSchemaError(
                        $this->rootTypeResolver->getTypeOutputName(),
                        'fullSchema',
                        sprintf(
                            $this->translationAPI->__('Retrieving the schema data via Introspection failed: \'%s\'. Please contact the admin.', 'pop-component-model'),
                            $error->getMessage()
                        )
                    );
                    // Assign and return `null` to denote there was an error
                    $schemaDefinition = null;
                } elseif ($useCache) {
                    // Store in the cache
                    $this->persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
                }
            }
            // Assign to in-memory cache
            $this->schemaInstances[$key] = $schemaDefinition;
        }
        return $this->schemaInstances[$key];
    }
}
