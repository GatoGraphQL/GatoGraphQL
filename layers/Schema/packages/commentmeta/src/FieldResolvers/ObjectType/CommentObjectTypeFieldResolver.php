<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;

class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CommentMetaTypeAPIInterface $commentMetaTypeAPI = null;
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final public function setCommentMetaTypeAPI(CommentMetaTypeAPIInterface $commentMetaTypeAPI): void
    {
        $this->commentMetaTypeAPI = $commentMetaTypeAPI;
    }
    final protected function getCommentMetaTypeAPI(): CommentMetaTypeAPIInterface
    {
        return $this->commentMetaTypeAPI ??= $this->instanceManager->getInstance(CommentMetaTypeAPIInterface::class);
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

    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        // if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                if (!$this->getCommentMetaTypeAPI()->validateIsMetaKeyAllowed($fieldArgs['key'])) {
                    return [
                        sprintf(
                            $this->getTranslationAPI()->__('There is no key with name \'%s\'', 'commentmeta'),
                            $fieldArgs['key']
                        ),
                    ];
                }
                break;
        }
        // }

        return parent::doResolveSchemaValidationErrorDescriptions($objectTypeResolver, $fieldName, $fieldArgs);
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
        $comment = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCommentMetaTypeAPI()->getCommentMeta(
                    $objectTypeResolver->getID($comment),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
