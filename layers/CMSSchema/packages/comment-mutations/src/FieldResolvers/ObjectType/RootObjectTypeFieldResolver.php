<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?RootAddCommentToCustomPostFilterInputObjectTypeResolver $rootAddCommentToCustomPostFilterInputObjectTypeResolver = null;
    private ?RootReplyCommentFilterInputObjectTypeResolver $rootReplyCommentFilterInputObjectTypeResolver = null;
    private ?RootAddCommentToCustomPostMutationPayloadObjectTypeResolver $commentCreateMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        /** @var AddCommentToCustomPostMutationResolver */
        return $this->addCommentToCustomPostMutationResolver ??= $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }
    final public function setRootAddCommentToCustomPostFilterInputObjectTypeResolver(RootAddCommentToCustomPostFilterInputObjectTypeResolver $rootAddCommentToCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver = $rootAddCommentToCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostFilterInputObjectTypeResolver(): RootAddCommentToCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootAddCommentToCustomPostFilterInputObjectTypeResolver */
        return $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootAddCommentToCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setRootReplyCommentFilterInputObjectTypeResolver(RootReplyCommentFilterInputObjectTypeResolver $rootReplyCommentFilterInputObjectTypeResolver): void
    {
        $this->rootReplyCommentFilterInputObjectTypeResolver = $rootReplyCommentFilterInputObjectTypeResolver;
    }
    final protected function getRootReplyCommentFilterInputObjectTypeResolver(): RootReplyCommentFilterInputObjectTypeResolver
    {
        /** @var RootReplyCommentFilterInputObjectTypeResolver */
        return $this->rootReplyCommentFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootReplyCommentFilterInputObjectTypeResolver::class);
    }
    final public function setRootAddCommentToCustomPostMutationPayloadObjectTypeResolver(RootAddCommentToCustomPostMutationPayloadObjectTypeResolver $commentCreateMutationPayloadObjectTypeResolver): void
    {
        $this->commentCreateMutationPayloadObjectTypeResolver = $commentCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver(): RootAddCommentToCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootAddCommentToCustomPostMutationPayloadObjectTypeResolver */
        return $this->commentCreateMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class);
    }
    final public function setPayloadableAddCommentToCustomPostMutationResolver(PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver): void
    {
        $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
    }
    final protected function getPayloadableAddCommentToCustomPostMutationResolver(): PayloadableAddCommentToCustomPostMutationResolver
    {
        /** @var PayloadableAddCommentToCustomPostMutationResolver */
        return $this->payloadableAddCommentToCustomPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostMutationResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var EngineModuleConfiguration */
        $moduleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        if ($moduleConfiguration->disableRedundantRootTypeMutationFields()) {
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

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if (!$usePayloadableCommentMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addCommentToCustomPost' => [
                MutationInputProperties::INPUT => $this->getRootAddCommentToCustomPostFilterInputObjectTypeResolver(),
            ],
            'replyComment' => [
                MutationInputProperties::INPUT => $this->getRootReplyCommentFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            MutationInputProperties::INPUT => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => $usePayloadableCommentMutations
                    ? $this->getPayloadableAddCommentToCustomPostMutationResolver()
                    : $this->getAddCommentToCustomPostMutationResolver(),
            default
                => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => $usePayloadableCommentMutations
                    ? $this->getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver()
                    : $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
