<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostFilterInputObjectTypeResolver;
use PoPSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentFilterInputObjectTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?RootAddCommentToCustomPostFilterInputObjectTypeResolver $rootAddCommentToCustomPostFilterInputObjectTypeResolver = null;
    private ?RootReplyCommentFilterInputObjectTypeResolver $rootReplyCommentFilterInputObjectTypeResolver = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        return $this->addCommentToCustomPostMutationResolver ??= $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }
    final public function setRootAddCommentToCustomPostFilterInputObjectTypeResolver(RootAddCommentToCustomPostFilterInputObjectTypeResolver $rootAddCommentToCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver = $rootAddCommentToCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostFilterInputObjectTypeResolver(): RootAddCommentToCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootAddCommentToCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setRootReplyCommentFilterInputObjectTypeResolver(RootReplyCommentFilterInputObjectTypeResolver $rootReplyCommentFilterInputObjectTypeResolver): void
    {
        $this->rootReplyCommentFilterInputObjectTypeResolver = $rootReplyCommentFilterInputObjectTypeResolver;
    }
    final protected function getRootReplyCommentFilterInputObjectTypeResolver(): RootReplyCommentFilterInputObjectTypeResolver
    {
        return $this->rootReplyCommentFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootReplyCommentFilterInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        /** @var EngineComponentConfiguration */
        $componentConfiguration = App::getComponent(EngineComponent::class)->getConfiguration();
        if ($componentConfiguration->disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            'addCommentToCustomPost',
            'replyComment',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCommentToCustomPost' => $this->__('Add a comment to a custom post', 'comment-mutations'),
            'replyComment' => $this->__('Reply a comment with another comment', 'comment-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addCommentToCustomPost' => [
                'input' => $this->getRootAddCommentToCustomPostFilterInputObjectTypeResolver(),
            ],
            'replyComment' => [
                'input' => $this->getRootReplyCommentFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'input' => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => $this->getAddCommentToCustomPostMutationResolver(),
            default
                => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
