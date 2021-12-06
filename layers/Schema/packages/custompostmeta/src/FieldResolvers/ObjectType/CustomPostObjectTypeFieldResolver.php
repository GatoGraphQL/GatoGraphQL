<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\FieldResolvers\ObjectType;

use InvalidArgumentException;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final public function setCustomPostMetaTypeAPI(CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI): void
    {
        $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
    }
    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        return $this->customPostMetaTypeAPI ??= $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
    }
    final public function setWithMetaInterfaceTypeFieldResolver(WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver): void
    {
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }
    final protected function getWithMetaInterfaceTypeFieldResolver(): WithMetaInterfaceTypeFieldResolver
    {
        return $this->withMetaInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithMetaInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPost = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                try {
                    $value = $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                        $objectTypeResolver->getID($customPost),
                        $fieldArgs['key'],
                        $fieldName === 'metaValue'
                    );
                } catch (InvalidArgumentException $e) {
                    // If the meta key is not in the allowlist, it will throw an exception
                    return new Error(
                        'meta-key-not-exists',
                        $e->getMessage()
                    );
                }
                return $value;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
