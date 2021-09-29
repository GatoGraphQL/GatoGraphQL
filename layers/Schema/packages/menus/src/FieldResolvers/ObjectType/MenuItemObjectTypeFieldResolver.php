<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver;

class MenuItemObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry;
    protected CMSHelperServiceInterface $cmsHelperService;
    protected URLScalarTypeResolver $urlScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected MenuItemObjectTypeResolver $menuItemObjectTypeResolver;

    #[Required]
    public function autowireMenuItemObjectTypeFieldResolver(
        MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
        CMSHelperServiceInterface $cmsHelperService,
        URLScalarTypeResolver $urlScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        MenuItemObjectTypeResolver $menuItemObjectTypeResolver,
    ): void {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
        $this->cmsHelperService = $cmsHelperService;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->menuItemObjectTypeResolver = $menuItemObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuItemObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            // This field is special in that it is retrieved from the registry
            'children',
            'localURLPath',
            // All other fields are properties in the object
            'label',
            'title',
            'url',
            'classes',
            'target',
            'description',
            'objectID',
            'parentID',
            'linkRelationship',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'children' => $this->menuItemObjectTypeResolver,
            'localURLPath' => $this->stringScalarTypeResolver,
            'label' => $this->stringScalarTypeResolver,
            'title' => $this->stringScalarTypeResolver,
            'url' => $this->urlScalarTypeResolver,
            'classes' => $this->stringScalarTypeResolver,
            'target' => $this->stringScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'objectID' => $this->idScalarTypeResolver,
            'parentID' => $this->idScalarTypeResolver,
            'linkRelationship' => $this->stringScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'children',
            'classes'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'children' => $this->translationAPI->__('Menu item children items', 'menus'),
            'label' => $this->translationAPI->__('Menu item label', 'menus'),
            'title' => $this->translationAPI->__('Menu item title', 'menus'),
            'localURLPath' => $this->translationAPI->__('Path of a local URL, or null if external URL', 'menus'),
            'url' => $this->translationAPI->__('Menu item URL', 'menus'),
            'classes' => $this->translationAPI->__('Menu item classes', 'menus'),
            'target' => $this->translationAPI->__('Menu item target', 'menus'),
            'description' => $this->translationAPI->__('Menu item additional attributes', 'menus'),
            'objectID' => $this->translationAPI->__('ID of the object linked to by the menu item ', 'menus'),
            'parentID' => $this->translationAPI->__('Menu item\'s parent ID', 'menus'),
            'linkRelationship' => $this->translationAPI->__('Link relationship (XFN)', 'menus'),
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
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var MenuItem */
        $menuItem = $object;
        switch ($fieldName) {
            case 'children':
                return array_keys($this->menuItemRuntimeRegistry->getMenuItemChildren($objectTypeResolver->getID($menuItem)));
            case 'localURLPath':
                $url = $menuItem->url;
                $pathURL = $this->cmsHelperService->getLocalURLPath($url);
                if ($pathURL === false) {
                    return null;
                }
                return $pathURL;
            // These are all properties of MenuItem
            case 'label':
            case 'title':
            case 'url':
            case 'classes':
            case 'target':
            case 'description':
            case 'objectID':
            case 'parentID':
            case 'linkRelationship':
                return $menuItem->$fieldName;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
