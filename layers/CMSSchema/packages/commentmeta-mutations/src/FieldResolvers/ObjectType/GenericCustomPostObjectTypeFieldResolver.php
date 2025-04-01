<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType\AbstractCommentObjectTypeFieldResolver;
use PoPCMSSchema\CommentMetaMutations\Module as CommentMetaMutationsModule;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration as CommentMetaMutationsModuleConfiguration;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\GenericCommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCommentObjectTypeFieldResolver extends AbstractCommentObjectTypeFieldResolver
{
    private ?GenericCommentObjectTypeResolver $genericCommentObjectTypeResolver = null;
    private ?GenericCommentDeleteMetaMutationPayloadObjectTypeResolver $genericCommentDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCommentAddMetaMutationPayloadObjectTypeResolver $genericCommentCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericCommentUpdateMetaMutationPayloadObjectTypeResolver $genericCommentUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCommentSetMetaMutationPayloadObjectTypeResolver $genericCommentSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCommentObjectTypeResolver(): GenericCommentObjectTypeResolver
    {
        if ($this->genericCommentObjectTypeResolver === null) {
            /** @var GenericCommentObjectTypeResolver */
            $genericCommentObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentObjectTypeResolver::class);
            $this->genericCommentObjectTypeResolver = $genericCommentObjectTypeResolver;
        }
        return $this->genericCommentObjectTypeResolver;
    }
    final protected function getGenericCommentDeleteMetaMutationPayloadObjectTypeResolver(): GenericCommentDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCommentDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCommentDeleteMetaMutationPayloadObjectTypeResolver */
            $genericCommentDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCommentDeleteMetaMutationPayloadObjectTypeResolver = $genericCommentDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCommentDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCommentAddMetaMutationPayloadObjectTypeResolver(): GenericCommentAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCommentCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCommentAddMetaMutationPayloadObjectTypeResolver */
            $genericCommentCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentAddMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCommentCreateMutationPayloadObjectTypeResolver = $genericCommentCreateMutationPayloadObjectTypeResolver;
        }
        return $this->genericCommentCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCommentUpdateMetaMutationPayloadObjectTypeResolver(): GenericCommentUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCommentUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCommentUpdateMetaMutationPayloadObjectTypeResolver */
            $genericCommentUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCommentUpdateMetaMutationPayloadObjectTypeResolver = $genericCommentUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCommentUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCommentSetMetaMutationPayloadObjectTypeResolver(): GenericCommentSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCommentSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCommentSetMetaMutationPayloadObjectTypeResolver */
            $genericCommentSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentSetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCommentSetMetaMutationPayloadObjectTypeResolver = $genericCommentSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCommentSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCommentObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CommentMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CommentMetaMutationsModule::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if (!$usePayloadableCommentMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getGenericCommentObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericCommentAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericCommentDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getGenericCommentSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericCommentUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
