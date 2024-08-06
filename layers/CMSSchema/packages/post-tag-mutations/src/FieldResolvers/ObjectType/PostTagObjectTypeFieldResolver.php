<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMutations\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\TagMutations\Module as TagMutationsModule;
use PoPCMSSchema\TagMutations\ModuleConfiguration as TagMutationsModuleConfiguration;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\DeletePostTagTermMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableDeletePostTagTermMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableUpdatePostTagTermMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\UpdatePostTagTermMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostTagDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostTagUpdateMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class PostTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostTagUpdateMutationPayloadObjectTypeResolver $postTagUpdateMutationPayloadObjectTypeResolver = null;
    private ?PostTagDeleteMutationPayloadObjectTypeResolver $postTagDeleteMutationPayloadObjectTypeResolver = null;
    private ?UpdatePostTagTermMutationResolver $updatePostTagTermMutationResolver = null;
    private ?DeletePostTagTermMutationResolver $deletePostTagTermMutationResolver = null;
    private ?PayloadableUpdatePostTagTermMutationResolver $payloadableUpdatePostTagTermMutationResolver = null;
    private ?PayloadableDeletePostTagTermMutationResolver $payloadableDeletePostTagTermMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final public function setPostTagUpdateMutationPayloadObjectTypeResolver(PostTagUpdateMutationPayloadObjectTypeResolver $postTagUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->postTagUpdateMutationPayloadObjectTypeResolver = $postTagUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostTagUpdateMutationPayloadObjectTypeResolver(): PostTagUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->postTagUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagUpdateMutationPayloadObjectTypeResolver */
            $postTagUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagUpdateMutationPayloadObjectTypeResolver::class);
            $this->postTagUpdateMutationPayloadObjectTypeResolver = $postTagUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->postTagUpdateMutationPayloadObjectTypeResolver;
    }
    final public function setPostTagDeleteMutationPayloadObjectTypeResolver(PostTagDeleteMutationPayloadObjectTypeResolver $postTagDeleteMutationPayloadObjectTypeResolver): void
    {
        $this->postTagDeleteMutationPayloadObjectTypeResolver = $postTagDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getPostTagDeleteMutationPayloadObjectTypeResolver(): PostTagDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->postTagDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagDeleteMutationPayloadObjectTypeResolver */
            $postTagDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagDeleteMutationPayloadObjectTypeResolver::class);
            $this->postTagDeleteMutationPayloadObjectTypeResolver = $postTagDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->postTagDeleteMutationPayloadObjectTypeResolver;
    }
    final public function setUpdatePostTagTermMutationResolver(UpdatePostTagTermMutationResolver $updatePostTagTermMutationResolver): void
    {
        $this->updatePostTagTermMutationResolver = $updatePostTagTermMutationResolver;
    }
    final protected function getUpdatePostTagTermMutationResolver(): UpdatePostTagTermMutationResolver
    {
        if ($this->updatePostTagTermMutationResolver === null) {
            /** @var UpdatePostTagTermMutationResolver */
            $updatePostTagTermMutationResolver = $this->instanceManager->getInstance(UpdatePostTagTermMutationResolver::class);
            $this->updatePostTagTermMutationResolver = $updatePostTagTermMutationResolver;
        }
        return $this->updatePostTagTermMutationResolver;
    }
    final public function setDeletePostTagTermMutationResolver(DeletePostTagTermMutationResolver $deletePostTagTermMutationResolver): void
    {
        $this->deletePostTagTermMutationResolver = $deletePostTagTermMutationResolver;
    }
    final protected function getDeletePostTagTermMutationResolver(): DeletePostTagTermMutationResolver
    {
        if ($this->deletePostTagTermMutationResolver === null) {
            /** @var DeletePostTagTermMutationResolver */
            $deletePostTagTermMutationResolver = $this->instanceManager->getInstance(DeletePostTagTermMutationResolver::class);
            $this->deletePostTagTermMutationResolver = $deletePostTagTermMutationResolver;
        }
        return $this->deletePostTagTermMutationResolver;
    }
    final public function setPayloadableUpdatePostTagTermMutationResolver(PayloadableUpdatePostTagTermMutationResolver $payloadableUpdatePostTagTermMutationResolver): void
    {
        $this->payloadableUpdatePostTagTermMutationResolver = $payloadableUpdatePostTagTermMutationResolver;
    }
    final protected function getPayloadableUpdatePostTagTermMutationResolver(): PayloadableUpdatePostTagTermMutationResolver
    {
        if ($this->payloadableUpdatePostTagTermMutationResolver === null) {
            /** @var PayloadableUpdatePostTagTermMutationResolver */
            $payloadableUpdatePostTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostTagTermMutationResolver::class);
            $this->payloadableUpdatePostTagTermMutationResolver = $payloadableUpdatePostTagTermMutationResolver;
        }
        return $this->payloadableUpdatePostTagTermMutationResolver;
    }
    final public function setPayloadableDeletePostTagTermMutationResolver(PayloadableDeletePostTagTermMutationResolver $payloadableDeletePostTagTermMutationResolver): void
    {
        $this->payloadableDeletePostTagTermMutationResolver = $payloadableDeletePostTagTermMutationResolver;
    }
    final protected function getPayloadableDeletePostTagTermMutationResolver(): PayloadableDeletePostTagTermMutationResolver
    {
        if ($this->payloadableDeletePostTagTermMutationResolver === null) {
            /** @var PayloadableDeletePostTagTermMutationResolver */
            $payloadableDeletePostTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostTagTermMutationResolver::class);
            $this->payloadableDeletePostTagTermMutationResolver = $payloadableDeletePostTagTermMutationResolver;
        }
        return $this->payloadableDeletePostTagTermMutationResolver;
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post tag', 'tag-mutations'),
            'delete' => $this->__('Delete the post tag', 'tag-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'update' => [
                'input' => $this->getTagTermUpdateInputObjectTypeResolver(),
            ],
            'delete' => [],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var TagMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagMutationsModule::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        return match ($fieldName) {
            'update' => $usePayloadableTagMutations
                ? $this->getPayloadableUpdatePostTagTermMutationResolver()
                : $this->getUpdatePostTagTermMutationResolver(),
            'delete' => $usePayloadableTagMutations
                ? $this->getPayloadableDeletePostTagTermMutationResolver()
                : $this->getDeletePostTagTermMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var TagMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagMutationsModule::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        if (!$usePayloadableTagMutations) {
            return match ($fieldName) {
                'update' => $this->getPostTagObjectTypeResolver(),
                'delete' => $this->getBooleanScalarTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update' => $this->getPostTagUpdateMutationPayloadObjectTypeResolver(),
            'delete' => $this->getPostTagDeleteMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
