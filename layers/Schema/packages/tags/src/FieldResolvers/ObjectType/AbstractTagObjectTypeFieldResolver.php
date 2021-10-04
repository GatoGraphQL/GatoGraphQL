<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractTagObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements TagAPIRequestedContractInterface
{
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver;

    #[Required]
    public function autowireAbstractTagObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
            'name',
            'description',
            'count',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->stringScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'count' => $this->intScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'count'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->translationAPI->__('Tag URL', 'pop-tags'),
            'urlPath' => $this->translationAPI->__('Tag URL path', 'pop-tags'),
            'name' => $this->translationAPI->__('Tag', 'pop-tags'),
            'slug' => $this->translationAPI->__('Tag slug', 'pop-tags'),
            'description' => $this->translationAPI->__('Tag description', 'pop-tags'),
            'count' => $this->translationAPI->__('Number of custom posts containing this tag', 'pop-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
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
        $tagTypeAPI = $this->getTagTypeAPI();
        $tag = $object;
        switch ($fieldName) {
            case 'url':
                return $tagTypeAPI->getTagURL($objectTypeResolver->getID($tag));

            case 'urlPath':
                return $tagTypeAPI->getTagURLPath($objectTypeResolver->getID($tag));

            case 'name':
                return $tagTypeAPI->getTagName($tag);

            case 'slug':
                return $tagTypeAPI->getTagSlug($tag);

            case 'description':
                return $tagTypeAPI->getTagDescription($tag);

            case 'count':
                return $tagTypeAPI->getTagItemCount($tag);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
