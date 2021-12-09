<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

class CustomPostObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;

    final public function setCustomPostMetaTypeAPI(CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI): void
    {
        $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
    }
    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        return $this->customPostMetaTypeAPI ??= $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
    }

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
                return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                    $objectTypeResolver->getID($customPost),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
