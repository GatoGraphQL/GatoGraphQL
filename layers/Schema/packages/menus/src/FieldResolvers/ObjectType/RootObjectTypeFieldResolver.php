<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\Menus\ModuleProcessors\MenuFilterInputContainerModuleProcessor;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    public function setMenuObjectTypeResolver(MenuObjectTypeResolver $menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        return $this->menuObjectTypeResolver ??= $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowireRootObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        MenuObjectTypeResolver $menuObjectTypeResolver,
        MenuTypeAPIInterface $menuTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        $this->menuTypeAPI = $menuTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'menu',
            'menus',
            'menuCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'menu' => $this->translationAPI->__('Get a menu', 'menus'),
            'menus' => $this->translationAPI->__('Get all menus', 'menus'),
            'menuCount' => $this->translationAPI->__('Count the number of menus', 'menus'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'menu' => $this->getMenuObjectTypeResolver(),
            'menus' => $this->getMenuObjectTypeResolver(),
            'menuCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'menus' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            'menuCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'menu' => [
                'id' => $this->getIdScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['menu' => 'id'] => $this->translationAPI->__('The ID of the menu', 'menus'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['menu' => 'id'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'menus' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUS],
            'menuCount' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
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
        switch ($fieldName) {
            case 'menu':
                // Validate the ID exists
                $menuID = $fieldArgs['id'];
                if ($this->getMenuTypeAPI()->getMenu($menuID) !== null) {
                    return $menuID;
                }
                return null;
        }

        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'menus':
                return $this->getMenuTypeAPI()->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'menuCount':
                return $this->getMenuTypeAPI()->getMenuCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
