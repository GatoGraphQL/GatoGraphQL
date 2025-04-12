<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\FieldResolvers\ObjectType;

use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\EntityObjectTypeFieldResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\UserMeta\Module;
use PoPCMSSchema\UserMeta\ModuleConfiguration;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    use EntityObjectTypeFieldResolverTrait;

    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;

    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        if ($this->userMetaTypeAPI === null) {
            /** @var UserMetaTypeAPIInterface */
            $userMetaTypeAPI = $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
            $this->userMetaTypeAPI = $userMetaTypeAPI;
        }
        return $this->userMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getUserMetaTypeAPI();
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserMetaKeysAsSensitiveData()) {
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
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaKeys':
                $metaKeys = [];
                $userMetaTypeAPI = $this->getUserMetaTypeAPI();
                $allUserMetaKeys = $userMetaTypeAPI->getUserMetaKeys($user);
                foreach ($allUserMetaKeys as $key) {
                    if (!$userMetaTypeAPI->validateIsMetaKeyAllowed($key)) {
                        continue;
                    }
                    $metaKeys[] = $key;
                }
                return $this->resolveMetaKeysValue(
                    $metaKeys,
                    $fieldDataAccessor,
                );
            case 'metaValue':
                $metaValue = $this->getUserMetaTypeAPI()->getUserMeta(
                    $user,
                    $fieldDataAccessor->getValue('key'),
                    true
                );
                // If it's an array, it must be a JSON object
                if (is_array($metaValue)) {
                    return MethodHelpers::recursivelyConvertAssociativeArrayToStdClass($metaValue);
                }
                return $metaValue;
            case 'metaValues':
                $metaValues = $this->getUserMetaTypeAPI()->getUserMeta(
                    $user,
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
                $allMeta = $this->getUserMetaTypeAPI()->getAllUserMeta($user);
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
