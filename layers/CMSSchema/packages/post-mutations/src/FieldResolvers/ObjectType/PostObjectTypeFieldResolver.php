<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostUpdateInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\PostUpdateInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\DeletePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableDeletePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\PostDeleteInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostUpdateMutationPayloadObjectTypeResolver $postUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver = null;
    private ?PostUpdateInputObjectTypeResolver $postUpdateInputObjectTypeResolver = null;

    private ?PostDeleteMutationPayloadObjectTypeResolver $postDeleteMutationPayloadObjectTypeResolver = null;
    private ?DeletePostMutationResolver $deletePostMutationResolver = null;
    private ?PayloadableDeletePostMutationResolver $payloadableDeletePostMutationResolver = null;
    private ?PostDeleteInputObjectTypeResolver $postDeleteInputObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getPostUpdateMutationPayloadObjectTypeResolver(): PostUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->postUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var PostUpdateMutationPayloadObjectTypeResolver */
            $postUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateMutationPayloadObjectTypeResolver::class);
            $this->postUpdateMutationPayloadObjectTypeResolver = $postUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->postUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        if ($this->updatePostMutationResolver === null) {
            /** @var UpdatePostMutationResolver */
            $updatePostMutationResolver = $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
            $this->updatePostMutationResolver = $updatePostMutationResolver;
        }
        return $this->updatePostMutationResolver;
    }
    final protected function getPayloadableUpdatePostMutationResolver(): PayloadableUpdatePostMutationResolver
    {
        if ($this->payloadableUpdatePostMutationResolver === null) {
            /** @var PayloadableUpdatePostMutationResolver */
            $payloadableUpdatePostMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostMutationResolver::class);
            $this->payloadableUpdatePostMutationResolver = $payloadableUpdatePostMutationResolver;
        }
        return $this->payloadableUpdatePostMutationResolver;
    }
    final protected function getPostUpdateInputObjectTypeResolver(): PostUpdateInputObjectTypeResolver
    {
        if ($this->postUpdateInputObjectTypeResolver === null) {
            /** @var PostUpdateInputObjectTypeResolver */
            $postUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateInputObjectTypeResolver::class);
            $this->postUpdateInputObjectTypeResolver = $postUpdateInputObjectTypeResolver;
        }
        return $this->postUpdateInputObjectTypeResolver;
    }

    final protected function getPostDeleteMutationPayloadObjectTypeResolver(): PostDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->postDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var PostDeleteMutationPayloadObjectTypeResolver */
            $postDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostDeleteMutationPayloadObjectTypeResolver::class);
            $this->postDeleteMutationPayloadObjectTypeResolver = $postDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->postDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getDeletePostMutationResolver(): DeletePostMutationResolver
    {
        if ($this->deletePostMutationResolver === null) {
            /** @var DeletePostMutationResolver */
            $deletePostMutationResolver = $this->instanceManager->getInstance(DeletePostMutationResolver::class);
            $this->deletePostMutationResolver = $deletePostMutationResolver;
        }
        return $this->deletePostMutationResolver;
    }
    final protected function getPayloadableDeletePostMutationResolver(): PayloadableDeletePostMutationResolver
    {
        if ($this->payloadableDeletePostMutationResolver === null) {
            /** @var PayloadableDeletePostMutationResolver */
            $payloadableDeletePostMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostMutationResolver::class);
            $this->payloadableDeletePostMutationResolver = $payloadableDeletePostMutationResolver;
        }
        return $this->payloadableDeletePostMutationResolver;
    }
    final protected function getPostDeleteInputObjectTypeResolver(): PostDeleteInputObjectTypeResolver
    {
        if ($this->postDeleteInputObjectTypeResolver === null) {
            /** @var PostDeleteInputObjectTypeResolver */
            $postDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(PostDeleteInputObjectTypeResolver::class);
            $this->postDeleteInputObjectTypeResolver = $postDeleteInputObjectTypeResolver;
        }
        return $this->postDeleteInputObjectTypeResolver;
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
            PostObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostUpdateInputObjectTypeResolver(): AbstractCustomPostUpdateInputObjectTypeResolver
    {
        return $this->getPostUpdateInputObjectTypeResolver();
    }

    protected function getCustomPostDeleteInputObjectTypeResolver(): AbstractDeleteCustomPostInputObjectTypeResolver
    {
        return $this->getPostDeleteInputObjectTypeResolver();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post', 'gatographql'),
            'delete' => $this->__('Delete the post', 'gatographql'),
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
                'input' => $this->getPostUpdateInputObjectTypeResolver(),
            ],
            'delete' => [
                'input' => $this->getPostDeleteInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePostMutationResolver()
                : $this->getUpdatePostMutationResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableDeletePostMutationResolver()
                : $this->getDeletePostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getPostUpdateMutationPayloadObjectTypeResolver()
                : $this->getPostObjectTypeResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getPostDeleteMutationPayloadObjectTypeResolver()
                : $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
