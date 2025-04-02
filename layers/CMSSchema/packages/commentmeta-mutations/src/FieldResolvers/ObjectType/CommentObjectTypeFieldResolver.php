<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType\AbstractCommentObjectTypeFieldResolver;
use PoPCMSSchema\CommentMetaMutations\Module as CommentMetaMutationsModule;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration as CommentMetaMutationsModuleConfiguration;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentObjectTypeFieldResolver extends AbstractCommentObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CommentDeleteMetaMutationPayloadObjectTypeResolver $commentDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?CommentAddMetaMutationPayloadObjectTypeResolver $commentCreateMutationPayloadObjectTypeResolver = null;
    private ?CommentUpdateMetaMutationPayloadObjectTypeResolver $commentUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?CommentSetMetaMutationPayloadObjectTypeResolver $commentSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final protected function getCommentDeleteMetaMutationPayloadObjectTypeResolver(): CommentDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->commentDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var CommentDeleteMetaMutationPayloadObjectTypeResolver */
            $commentDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->commentDeleteMetaMutationPayloadObjectTypeResolver = $commentDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->commentDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getCommentAddMetaMutationPayloadObjectTypeResolver(): CommentAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->commentCreateMutationPayloadObjectTypeResolver === null) {
            /** @var CommentAddMetaMutationPayloadObjectTypeResolver */
            $commentCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentAddMetaMutationPayloadObjectTypeResolver::class);
            $this->commentCreateMutationPayloadObjectTypeResolver = $commentCreateMutationPayloadObjectTypeResolver;
        }
        return $this->commentCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getCommentUpdateMetaMutationPayloadObjectTypeResolver(): CommentUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->commentUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var CommentUpdateMetaMutationPayloadObjectTypeResolver */
            $commentUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->commentUpdateMetaMutationPayloadObjectTypeResolver = $commentUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->commentUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getCommentSetMetaMutationPayloadObjectTypeResolver(): CommentSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->commentSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var CommentSetMetaMutationPayloadObjectTypeResolver */
            $commentSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentSetMetaMutationPayloadObjectTypeResolver::class);
            $this->commentSetMetaMutationPayloadObjectTypeResolver = $commentSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->commentSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
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
                    => $this->getCommentObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getCommentAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getCommentDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getCommentSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getCommentUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
