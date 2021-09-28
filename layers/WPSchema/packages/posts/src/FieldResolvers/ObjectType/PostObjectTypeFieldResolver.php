<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use WP_Post;

class PostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    public function __construct(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ) {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'postFormat',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'postFormat' => $this->translationAPI->__('Retrieve the format slug for a post', 'posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'postFormat' => $this->stringScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postFormat' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
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
        /** @var WP_Post */
        $post = $object;
        switch ($fieldName) {
            case 'postFormat':
                $postFormat = \get_post_format($post);
                // @see https://developer.wordpress.org/reference/functions/get_post_format/#more-information
                if ($postFormat === false) {
                    return 'standard';
                }
                return $postFormat;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
