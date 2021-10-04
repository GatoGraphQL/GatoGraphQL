<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CustomPostMetaTypeAPIInterface $customPostMetaAPI;
    protected WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver;

    #[Required]
    final public function autowireCustomPostObjectTypeFieldResolver(
        CustomPostMetaTypeAPIInterface $customPostMetaAPI,
        WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver,
    ): void {
        $this->customPostMetaAPI = $customPostMetaAPI;
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
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
            $this->withMetaInterfaceTypeFieldResolver,
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
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPost = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $this->customPostMetaAPI->getCustomPostMeta(
                    $objectTypeResolver->getID($customPost),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
