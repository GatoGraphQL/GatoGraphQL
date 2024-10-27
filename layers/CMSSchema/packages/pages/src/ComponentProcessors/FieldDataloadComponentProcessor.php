<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE = 'dataload-relationalfields-page';

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE,
        );
    }

    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    public function getObjectIDOrIDs(Component $component, array &$props, array &$data_properties): string|int|array|null
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getQueriedDBObjectID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getPageObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
