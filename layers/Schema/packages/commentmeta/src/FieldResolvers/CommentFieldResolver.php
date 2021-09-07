<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\FieldResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CommentMeta\Facades\CommentMetaTypeAPIFacade;
use PoPSchema\Comments\TypeResolvers\Object\CommentTypeResolver;
use PoPSchema\Meta\FieldInterfaceResolvers\WithMetaFieldInterfaceResolver;

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
            'metaValue',
            'metaValues',
        ];
    }

    /**
     * Get the Schema Definition from the Interface
     */
    protected function doGetSchemaDefinitionResolver(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): FieldSchemaDefinitionResolverInterface | FieldInterfaceSchemaDefinitionResolverInterface {

        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                /** @var WithMetaFieldInterfaceResolver */
                $resolver = $this->instanceManager->getInstance(WithMetaFieldInterfaceResolver::class);
                return $resolver;
        }

        return parent::doGetSchemaDefinitionResolver($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
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
            case 'metaValue':
            case 'metaValues':
                return $commentMetaAPI->getCommentMeta(
                    $relationalTypeResolver->getID($comment),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
