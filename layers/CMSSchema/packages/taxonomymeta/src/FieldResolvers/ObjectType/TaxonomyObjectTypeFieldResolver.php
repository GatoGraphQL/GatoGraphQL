<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\EntityObjectTypeFieldResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPCMSSchema\TaxonomyMeta\Module;
use PoPCMSSchema\TaxonomyMeta\ModuleConfiguration;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class TaxonomyObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    use EntityObjectTypeFieldResolverTrait;

    private ?TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI = null;

    final protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface
    {
        if ($this->taxonomyMetaTypeAPI === null) {
            /** @var TaxonomyMetaTypeAPIInterface */
            $taxonomyMetaTypeAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeAPIInterface::class);
            $this->taxonomyMetaTypeAPI = $taxonomyMetaTypeAPI;
        }
        return $this->taxonomyMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTaxonomyObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatTaxonomyMetaKeysAsSensitiveData()) {
            $sensitiveFieldArgNames[] = 'metaKeys';
        }
        return $sensitiveFieldArgNames;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $taxonomyTerm = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaKeys':
                $metaKeys = [];
                $taxonomyMetaTypeAPI = $this->getTaxonomyMetaTypeAPI();
                $allTaxonomyTermMetaKeys = $taxonomyMetaTypeAPI->getTaxonomyTermMetaKeys($taxonomyTerm);
                foreach ($allTaxonomyTermMetaKeys as $key) {
                    if (!$taxonomyMetaTypeAPI->validateIsMetaKeyAllowed($key)) {
                        continue;
                    }
                    $metaKeys[] = $key;
                }
                return $this->resolveMetaKeysValue(
                    $metaKeys,
                    $fieldDataAccessor,
                );
            case 'metaValue':
                $metaValue = $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta(
                    $taxonomyTerm,
                    $fieldDataAccessor->getValue('key'),
                    true
                );
                // If it's an array, it must be a JSON object
                if (is_array($metaValue)) {
                    return MethodHelpers::recursivelyConvertAssociativeArrayToStdClass($metaValue);
                }
                return $metaValue;
            case 'metaValues':
                $metaValues = $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta(
                    $taxonomyTerm,
                    $fieldDataAccessor->getValue('key'),
                    false
                );
                if (!is_array($metaValues)) {
                    return $metaValues;
                }
                foreach ($metaValues as $index => $metaValue) {
                    // If it's an array, it must be a JSON object
                    if (!is_array($metaValue)) {
                        continue;
                    }
                    $metaValues[$index] = MethodHelpers::recursivelyConvertAssociativeArrayToStdClass($metaValue);
                }
                return $metaValues;
            case 'meta':
                $meta = [];
                $allMeta = $this->getTaxonomyMetaTypeAPI()->getAllTaxonomyTermMeta($taxonomyTerm);
                /** @var string[] */
                $keys = $fieldDataAccessor->getValue('keys');
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $allMeta)) {
                        continue;
                    }
                    $meta[$key] = $allMeta[$key];
                }
                return (object) $meta;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
