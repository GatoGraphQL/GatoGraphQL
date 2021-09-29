<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;

class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CommentMetaTypeAPIInterface $commentMetaAPI;
    protected WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver;

    #[Required]
    public function autowireCommentObjectTypeFieldResolver(
        CommentMetaTypeAPIInterface $commentMetaAPI,
        WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver,
    ): void {
        $this->commentMetaAPI = $commentMetaAPI;
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
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
        $comment = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $this->commentMetaAPI->getCommentMeta(
                    $objectTypeResolver->getID($comment),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
