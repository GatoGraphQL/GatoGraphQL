<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class MenuItemObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry = null;
    private ?CMSHelperServiceInterface $cmsHelperService = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MenuItemObjectTypeResolver $menuItemObjectTypeResolver = null;

    final public function setMenuItemRuntimeRegistry(MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry): void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    final protected function getMenuItemRuntimeRegistry(): MenuItemRuntimeRegistryInterface
    {
        /** @var MenuItemRuntimeRegistryInterface */
        return $this->menuItemRuntimeRegistry ??= $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }
    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setMenuItemObjectTypeResolver(MenuItemObjectTypeResolver $menuItemObjectTypeResolver): void
    {
        $this->menuItemObjectTypeResolver = $menuItemObjectTypeResolver;
    }
    final protected function getMenuItemObjectTypeResolver(): MenuItemObjectTypeResolver
    {
        /** @var MenuItemObjectTypeResolver */
        return $this->menuItemObjectTypeResolver ??= $this->instanceManager->getInstance(MenuItemObjectTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuItemObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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
        return match ($fieldName) {
            'children' => $this->getMenuItemObjectTypeResolver(),
            'localURLPath' => $this->getStringScalarTypeResolver(),
            'label' => $this->getStringScalarTypeResolver(),
            'title' => $this->getStringScalarTypeResolver(),
            'url' => $this->getURLScalarTypeResolver(),
            'classes' => $this->getStringScalarTypeResolver(),
            'target' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'objectID' => $this->getIDScalarTypeResolver(),
            'parentID' => $this->getIDScalarTypeResolver(),
            'linkRelationship' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'children',
            'classes'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'children' => $this->__('Menu item children items', 'menus'),
            'label' => $this->__('Menu item label', 'menus'),
            'title' => $this->__('Menu item title', 'menus'),
            'localURLPath' => $this->__('Path of a local URL, or null if external URL', 'menus'),
            'url' => $this->__('Menu item URL', 'menus'),
            'classes' => $this->__('Menu item classes', 'menus'),
            'target' => $this->__('Menu item target', 'menus'),
            'description' => $this->__('Menu item additional attributes', 'menus'),
            'objectID' => $this->__('ID of the object linked to by the menu item ', 'menus'),
            'parentID' => $this->__('Menu item\'s parent ID', 'menus'),
            'linkRelationship' => $this->__('Link relationship (XFN)', 'menus'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var MenuItem */
        $menuItem = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'children':
                return array_keys($this->getMenuItemRuntimeRegistry()->getMenuItemChildren($menuItem));
            case 'localURLPath':
                $url = $menuItem->url;
                return $this->getCMSHelperService()->getLocalURLPath($url);
            // These are all properties of MenuItem
            // Commented out since this is the default FieldResolver's response
            // case 'label':
            // case 'title':
            // case 'url':
            // case 'classes':
            // case 'target':
            // case 'description':
            // case 'objectID':
            // case 'parentID':
            // case 'linkRelationship':
            //     return $menuItem->$fieldName;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
