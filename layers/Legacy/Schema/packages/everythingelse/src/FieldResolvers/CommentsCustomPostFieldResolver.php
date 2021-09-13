<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;

class CommentsCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'commentsURL',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'commentsURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'commentsURL' => $this->translationAPI->__('URL of the comments section in the post page', 'pop-comments'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $resultItem;
        switch ($fieldName) {
            case 'commentsURL':
                // @todo: fix this, add the ?#comment_id=... to the post URL
                return $objectTypeResolver->resolveValue($post, 'url', $variables, $expressions, $options);
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
