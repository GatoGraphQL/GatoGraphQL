<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\UserRoles\Component;
use PoPSchema\UserRoles\ComponentConfiguration;

trait RolesObjectTypeFieldResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;
    abstract protected function getStringScalarTypeResolver(): StringScalarTypeResolver;

    public function getFieldNamesToResolve(): array
    {
        return [
            'roles',
            'capabilities',
        ];
    }

    public function getAdminFieldNames(): array
    {
        $adminFieldNames = parent::getAdminFieldNames();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->treatUserRoleAsAdminData()) {
            $adminFieldNames[] = 'roles';
        }
        if ($componentConfiguration->treatUserCapabilityAsAdminData()) {
            $adminFieldNames[] = 'capabilities';
        }
        return $adminFieldNames;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'roles' => $this->getStringScalarTypeResolver(),
            'capabilities' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'roles' => $this->getTranslationaAPI()->__('All user roles', 'user-roles'),
            'capabilities' => $this->getTranslationaAPI()->__('All user capabilities', 'user-roles'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }
}
