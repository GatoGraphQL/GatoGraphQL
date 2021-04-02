<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Meta\FieldInterfaceResolvers\WithMetaFieldInterfaceResolver;
use PoPSchema\CommentMeta\Facades\CommentMetaTypeAPIFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;

class CommentFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            CommentTypeResolver::class,
        ];
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            WithMetaFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'meta',
        ];
    }

    /**
     * By returning `null`, the schema definition comes from the interface
     */
    public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return null;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $commentMetaAPI = CommentMetaTypeAPIFacade::getInstance();
        $comment = $resultItem;
        switch ($fieldName) {
            case 'meta':
                return $commentMetaAPI->getCommentMeta(
                    $typeResolver->getID($comment),
                    $fieldArgs['key'],
                    $fieldArgs['single']
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
