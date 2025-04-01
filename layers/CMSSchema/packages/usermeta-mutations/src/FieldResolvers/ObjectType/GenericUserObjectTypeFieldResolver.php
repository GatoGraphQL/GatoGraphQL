<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType\AbstractUserObjectTypeFieldResolver;
use PoPCMSSchema\UserMetaMutations\Module as UserMetaMutationsModule;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration as UserMetaMutationsModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\GenericUserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserObjectTypeFieldResolver extends AbstractUserObjectTypeFieldResolver
{
    private ?GenericUserObjectTypeResolver $genericUserObjectTypeResolver = null;
    private ?GenericUserDeleteMetaMutationPayloadObjectTypeResolver $genericUserDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericUserAddMetaMutationPayloadObjectTypeResolver $genericUserCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericUserUpdateMetaMutationPayloadObjectTypeResolver $genericUserUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericUserSetMetaMutationPayloadObjectTypeResolver $genericUserSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericUserObjectTypeResolver(): GenericUserObjectTypeResolver
    {
        if ($this->genericUserObjectTypeResolver === null) {
            /** @var GenericUserObjectTypeResolver */
            $genericUserObjectTypeResolver = $this->instanceManager->getInstance(GenericUserObjectTypeResolver::class);
            $this->genericUserObjectTypeResolver = $genericUserObjectTypeResolver;
        }
        return $this->genericUserObjectTypeResolver;
    }
    final protected function getGenericUserDeleteMetaMutationPayloadObjectTypeResolver(): GenericUserDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericUserDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericUserDeleteMetaMutationPayloadObjectTypeResolver */
            $genericUserDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericUserDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->genericUserDeleteMetaMutationPayloadObjectTypeResolver = $genericUserDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericUserDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericUserAddMetaMutationPayloadObjectTypeResolver(): GenericUserAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericUserCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericUserAddMetaMutationPayloadObjectTypeResolver */
            $genericUserCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericUserAddMetaMutationPayloadObjectTypeResolver::class);
            $this->genericUserCreateMutationPayloadObjectTypeResolver = $genericUserCreateMutationPayloadObjectTypeResolver;
        }
        return $this->genericUserCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericUserUpdateMetaMutationPayloadObjectTypeResolver(): GenericUserUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericUserUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericUserUpdateMetaMutationPayloadObjectTypeResolver */
            $genericUserUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericUserUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->genericUserUpdateMetaMutationPayloadObjectTypeResolver = $genericUserUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericUserUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericUserSetMetaMutationPayloadObjectTypeResolver(): GenericUserSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericUserSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericUserSetMetaMutationPayloadObjectTypeResolver */
            $genericUserSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericUserSetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericUserSetMetaMutationPayloadObjectTypeResolver = $genericUserSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericUserSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericUserObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var UserMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(UserMetaMutationsModule::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if (!$usePayloadableUserMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getGenericUserObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericUserAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericUserDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getGenericUserSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericUserUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
