<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE = 'dataload-relationalfields-page';

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;

    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE],
        );
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array | null
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getPageObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
