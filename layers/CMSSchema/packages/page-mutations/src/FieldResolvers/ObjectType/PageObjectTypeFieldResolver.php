<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableUpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\UpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\PageUpdateInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
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

    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final public function setPageUpdateMutationPayloadObjectTypeResolver(PageUpdateMutationPayloadObjectTypeResolver $pageUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->pageUpdateMutationPayloadObjectTypeResolver = $pageUpdateMutationPayloadObjectTypeResolver;
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
    final public function setUpdatePageMutationResolver(UpdatePageMutationResolver $updatePageMutationResolver): void
    {
        $this->updatePageMutationResolver = $updatePageMutationResolver;
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
    final public function setPayloadableUpdatePageMutationResolver(PayloadableUpdatePageMutationResolver $payloadableUpdatePageMutationResolver): void
    {
        $this->payloadableUpdatePageMutationResolver = $payloadableUpdatePageMutationResolver;
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
    final public function setPageUpdateInputObjectTypeResolver(PageUpdateInputObjectTypeResolver $pageUpdateInputObjectTypeResolver): void
    {
        $this->pageUpdateInputObjectTypeResolver = $pageUpdateInputObjectTypeResolver;
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the page', 'page-mutations'),
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
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
