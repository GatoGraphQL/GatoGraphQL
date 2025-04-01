<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType\AbstractRootCommentCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CommentMetaMutations\Module;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootAddCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootDeleteCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootSetCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootUpdateCommentMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootCommentCRUDObjectTypeFieldResolver extends AbstractRootCommentCRUDObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?RootDeleteCommentMetaMutationPayloadObjectTypeResolver $rootDeleteCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetCommentMetaMutationPayloadObjectTypeResolver $rootSetCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateCommentMetaMutationPayloadObjectTypeResolver $rootUpdateCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddCommentMetaMutationPayloadObjectTypeResolver $rootAddCommentMetaMutationPayloadObjectTypeResolver = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final protected function getRootDeleteCommentMetaMutationPayloadObjectTypeResolver(): RootDeleteCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteCommentMetaMutationPayloadObjectTypeResolver */
            $rootDeleteCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteCommentMetaMutationPayloadObjectTypeResolver = $rootDeleteCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetCommentMetaMutationPayloadObjectTypeResolver(): RootSetCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetCommentMetaMutationPayloadObjectTypeResolver */
            $rootSetCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetCommentMetaMutationPayloadObjectTypeResolver = $rootSetCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateCommentMetaMutationPayloadObjectTypeResolver(): RootUpdateCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateCommentMetaMutationPayloadObjectTypeResolver */
            $rootUpdateCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateCommentMetaMutationPayloadObjectTypeResolver = $rootUpdateCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddCommentMetaMutationPayloadObjectTypeResolver(): RootAddCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddCommentMetaMutationPayloadObjectTypeResolver */
            $rootAddCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddCommentMetaMutationPayloadObjectTypeResolver = $rootAddCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddCommentMetaMutationPayloadObjectTypeResolver;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if ($usePayloadableCommentMetaMutations) {
            return match ($fieldName) {
                'addCommentMeta',
                'addCommentMetas',
                'addCommentMetaMutationPayloadObjects'
                    => $this->getRootAddCommentMetaMutationPayloadObjectTypeResolver(),
                'updateCommentMeta',
                'updateCommentMetas',
                'updateCommentMetaMutationPayloadObjects'
                    => $this->getRootUpdateCommentMetaMutationPayloadObjectTypeResolver(),
                'deleteCommentMeta',
                'deleteCommentMetas',
                'deleteCommentMetaMutationPayloadObjects'
                    => $this->getRootDeleteCommentMetaMutationPayloadObjectTypeResolver(),
                'setCommentMeta',
                'setCommentMetas',
                'setCommentMetaMutationPayloadObjects'
                    => $this->getRootSetCommentMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCommentMeta',
            'addCommentMetas',
            'updateCommentMeta',
            'updateCommentMetas',
            'deleteCommentMeta',
            'deleteCommentMetas',
            'setCommentMeta',
            'setCommentMetas'
                => $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
