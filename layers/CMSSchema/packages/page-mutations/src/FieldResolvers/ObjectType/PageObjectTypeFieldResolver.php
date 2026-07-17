<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostUpdateInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableUpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\UpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\PageUpdateInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\DeletePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableDeletePageMutationResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\PageDeleteInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageUpdateMutationPayloadObjectTypeResolver $pageUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdatePageMutationResolver $updatePageMutationResolver = null;
    private ?PayloadableUpdatePageMutationResolver $payloadableUpdatePageMutationResolver = null;
    private ?PageUpdateInputObjectTypeResolver $pageUpdateInputObjectTypeResolver = null;

    private ?PageDeleteMutationPayloadObjectTypeResolver $pageDeleteMutationPayloadObjectTypeResolver = null;
    private ?DeletePageMutationResolver $deletePageMutationResolver = null;
    private ?PayloadableDeletePageMutationResolver $payloadableDeletePageMutationResolver = null;
    private ?PageDeleteInputObjectTypeResolver $pageDeleteInputObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPageUpdateMutationPayloadObjectTypeResolver(): PageUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->pageUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var PageUpdateMutationPayloadObjectTypeResolver */
            $pageUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageUpdateMutationPayloadObjectTypeResolver::class);
            $this->pageUpdateMutationPayloadObjectTypeResolver = $pageUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->pageUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getUpdatePageMutationResolver(): UpdatePageMutationResolver
    {
        if ($this->updatePageMutationResolver === null) {
            /** @var UpdatePageMutationResolver */
            $updatePageMutationResolver = $this->instanceManager->getInstance(UpdatePageMutationResolver::class);
            $this->updatePageMutationResolver = $updatePageMutationResolver;
        }
        return $this->updatePageMutationResolver;
    }
    final protected function getPayloadableUpdatePageMutationResolver(): PayloadableUpdatePageMutationResolver
    {
        if ($this->payloadableUpdatePageMutationResolver === null) {
            /** @var PayloadableUpdatePageMutationResolver */
            $payloadableUpdatePageMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePageMutationResolver::class);
            $this->payloadableUpdatePageMutationResolver = $payloadableUpdatePageMutationResolver;
        }
        return $this->payloadableUpdatePageMutationResolver;
    }
    final protected function getPageUpdateInputObjectTypeResolver(): PageUpdateInputObjectTypeResolver
    {
        if ($this->pageUpdateInputObjectTypeResolver === null) {
            /** @var PageUpdateInputObjectTypeResolver */
            $pageUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(PageUpdateInputObjectTypeResolver::class);
            $this->pageUpdateInputObjectTypeResolver = $pageUpdateInputObjectTypeResolver;
        }
        return $this->pageUpdateInputObjectTypeResolver;
    }

    final protected function getPageDeleteMutationPayloadObjectTypeResolver(): PageDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->pageDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var PageDeleteMutationPayloadObjectTypeResolver */
            $pageDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageDeleteMutationPayloadObjectTypeResolver::class);
            $this->pageDeleteMutationPayloadObjectTypeResolver = $pageDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->pageDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getDeletePageMutationResolver(): DeletePageMutationResolver
    {
        if ($this->deletePageMutationResolver === null) {
            /** @var DeletePageMutationResolver */
            $deletePageMutationResolver = $this->instanceManager->getInstance(DeletePageMutationResolver::class);
            $this->deletePageMutationResolver = $deletePageMutationResolver;
        }
        return $this->deletePageMutationResolver;
    }
    final protected function getPayloadableDeletePageMutationResolver(): PayloadableDeletePageMutationResolver
    {
        if ($this->payloadableDeletePageMutationResolver === null) {
            /** @var PayloadableDeletePageMutationResolver */
            $payloadableDeletePageMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePageMutationResolver::class);
            $this->payloadableDeletePageMutationResolver = $payloadableDeletePageMutationResolver;
        }
        return $this->payloadableDeletePageMutationResolver;
    }
    final protected function getPageDeleteInputObjectTypeResolver(): PageDeleteInputObjectTypeResolver
    {
        if ($this->pageDeleteInputObjectTypeResolver === null) {
            /** @var PageDeleteInputObjectTypeResolver */
            $pageDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(PageDeleteInputObjectTypeResolver::class);
            $this->pageDeleteInputObjectTypeResolver = $pageDeleteInputObjectTypeResolver;
        }
        return $this->pageDeleteInputObjectTypeResolver;
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
            PageObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostUpdateInputObjectTypeResolver(): AbstractCustomPostUpdateInputObjectTypeResolver
    {
        return $this->getPageUpdateInputObjectTypeResolver();
    }

    protected function getCustomPostDeleteInputObjectTypeResolver(): AbstractDeleteCustomPostInputObjectTypeResolver
    {
        return $this->getPageDeleteInputObjectTypeResolver();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the page', 'gatographql'),
            'delete' => $this->__('Delete the page', 'gatographql'),
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
                'input' => $this->getPageUpdateInputObjectTypeResolver(),
            ],
            'delete' => [
                'input' => $this->getPageDeleteInputObjectTypeResolver(),
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
                ? $this->getPayloadableUpdatePageMutationResolver()
                : $this->getUpdatePageMutationResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableDeletePageMutationResolver()
                : $this->getDeletePageMutationResolver(),
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
                ? $this->getPageUpdateMutationPayloadObjectTypeResolver()
                : $this->getPageObjectTypeResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getPageDeleteMutationPayloadObjectTypeResolver()
                : $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
