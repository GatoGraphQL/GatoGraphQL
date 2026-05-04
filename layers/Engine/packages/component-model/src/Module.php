<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;
use PoP\ComponentModel\Container\ServiceTags\MandatoryFieldDirectiveServiceTagInterface;
use PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ApplicationEvents;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Definitions\Module::class,
            \PoP\GraphQLParser\Module::class,
            \PoP\LooseContracts\Module::class,
            \PoP\ComponentRouting\Module::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    public function getServiceAutoconfigurations(): array
    {
        return [
            ComponentFilterInterface::class,
            DataStructureFormatterInterface::class,
            DynamicVariableDefinerFieldDirectiveResolverInterface::class,
            FieldDirectiveResolverInterface::class,
            MandatoryFieldDirectiveServiceTagInterface::class,
            MetaFieldDirectiveResolverInterface::class,
            ObjectSerializerInterface::class,
            OperationDependencyDefinerFieldDirectiveResolverInterface::class,
            TypeResolverInterface::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }

    public function moduleLoaded(): void
    {
        parent::moduleLoaded();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::MODULE_LOADED);
    }

    public function preBoot(): void
    {
        parent::preBoot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::PRE_BOOT);
    }

    public function boot(): void
    {
        parent::boot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::BOOT);
    }

    public function afterBoot(): void
    {
        parent::afterBoot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::AFTER_BOOT);
    }
}
