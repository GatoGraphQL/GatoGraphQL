<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMeta\Module;
use PoPCMSSchema\CustomPostMeta\ModuleConfiguration;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\EntityObjectTypeFieldResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    use EntityObjectTypeFieldResolverTrait;

    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;

    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        if ($this->customPostMetaTypeAPI === null) {
            /** @var CustomPostMetaTypeAPIInterface */
            $customPostMetaTypeAPI = $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
            $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
        }
        return $this->customPostMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatCustomPostMetaKeysAsSensitiveData()) {
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
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaKeys':
                $metaKeys = [];
                $customPostMetaTypeAPI = $this->getCustomPostMetaTypeAPI();
                $allCustomPostMetaKeys = $customPostMetaTypeAPI->getCustomPostMetaKeys($customPost);
                foreach ($allCustomPostMetaKeys as $key) {
                    if (!$customPostMetaTypeAPI->validateIsMetaKeyAllowed($key)) {
                        continue;
                    }
                    $metaKeys[] = $key;
                }
                return $this->resolveMetaKeysValue(
                    $metaKeys,
                    $objectTypeResolver,
                    $object,
                    $fieldDataAccessor,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            case 'metaValue':
                $metaValue = $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                    $customPost,
                    $fieldDataAccessor->getValue('key'),
                    true
                );
                // If it's an array, it must be a JSON object
                if (is_array($metaValue)) {
                    return MethodHelpers::recursivelyConvertAssociativeArrayToStdClass($metaValue);
                }
                return $metaValue;
            case 'metaValues':
                $metaValues = $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                    $customPost,
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
                $allMeta = $this->getCustomPostMetaTypeAPI()->getAllCustomPostMeta($customPost);
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
