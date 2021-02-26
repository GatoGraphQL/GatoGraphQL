<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoPSchema\Menus\TypeResolvers\MenuTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Menus\TypeResolvers\MenuItemTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Menus\TypeDataLoaders\MenuItemTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;

class MenuFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(MenuTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'items',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'items' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'items',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'items' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $cmsmenusresolver = \PoPSchema\Menus\ObjectPropertyResolverFactory::getInstance();
        $menu = $resultItem;
        switch ($fieldName) {
            case 'items':
                // Load needed values for the menu-items
                $instanceManager = InstanceManagerFacade::getInstance();
                /**
                 * @var MenuItemTypeDataLoader
                 */
                $menuItemTypeDataLoader = $instanceManager->getInstance(MenuItemTypeDataLoader::class);
                $menuID = $cmsmenusresolver->getMenuTermId($menu);
                $items = $menuItemTypeDataLoader->getObjects([$menuID])[0];

                // Load these item data-fields. If other set needed, create another $field
                $item_data_fields = array('id', 'title', 'alt', 'classes', 'url', 'target', 'menuItemParent', 'objectID', 'additionalAttrs');
                $value = array();
                if ($items) {
                    /**
                     * @var MenuItemTypeResolver
                     */
                    $menuItemTypeResolver = $instanceManager->getInstance(MenuItemTypeResolver::class);
                    foreach ($items as $item) {
                        $item_value = array();
                        foreach ($item_data_fields as $item_data_field) {
                            $menuItemValue = $menuItemTypeResolver->resolveValue($item, $item_data_field, $variables, $expressions, $options);
                            if (GeneralUtils::isError($menuItemValue)) {
                                return $menuItemValue;
                            }
                            $item_value[$item_data_field] = $menuItemValue;
                        }
                        $value[] = $item_value;
                    }
                }
                return $value;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
