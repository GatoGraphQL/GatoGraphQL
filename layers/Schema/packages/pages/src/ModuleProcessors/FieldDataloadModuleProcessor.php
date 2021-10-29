<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use Symfony\Contracts\Service\Attribute\Required;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_PAGE = 'dataload-relationalfields-page';

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;

    public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }

    //#[Required]
    final public function autowireFieldDataloadModuleProcessor(
        PageObjectTypeResolver $pageObjectTypeResolver,
    ): void {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getPageObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
