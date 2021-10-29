<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CommentMetaTypeAPIInterface $commentMetaAPI = null;
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    public function setCommentMetaTypeAPI(CommentMetaTypeAPIInterface $commentMetaAPI): void
    {
        $this->commentMetaAPI = $commentMetaAPI;
    }
    protected function getCommentMetaTypeAPI(): CommentMetaTypeAPIInterface
    {
        return $this->commentMetaAPI ??= $this->instanceManager->getInstance(CommentMetaTypeAPIInterface::class);
    }
    public function setWithMetaInterfaceTypeFieldResolver(WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver): void
    {
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }
    protected function getWithMetaInterfaceTypeFieldResolver(): WithMetaInterfaceTypeFieldResolver
    {
        return $this->withMetaInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
    }

    //#[Required]
    final public function autowireCommentObjectTypeFieldResolver(
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
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $comment = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCommentMetaAPI()->getCommentMeta(
                    $objectTypeResolver->getID($comment),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
