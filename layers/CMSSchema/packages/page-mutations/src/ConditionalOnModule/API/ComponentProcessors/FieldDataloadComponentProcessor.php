<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\PageMutations\ComponentProcessors\PageMutationFilterInputContainerComponentProcessor;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGELIST = 'dataload-relationalfields-mypagelist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGECOUNT = 'dataload-relationalfields-mypagecount';

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

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
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        if ($this->listQueryInputOutputHandler === null) {
            /** @var ListQueryInputOutputHandler */
            $listQueryInputOutputHandler = $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
            $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
        }
        return $this->listQueryInputOutputHandler;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGELIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGECOUNT,
        );
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGELIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGECOUNT:
                return $this->getPageObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGELIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGELIST:
                return new Component(PageMutationFilterInputContainerComponentProcessor::class, PageMutationFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_MYPAGES);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPAGECOUNT:
                return new Component(PageMutationFilterInputContainerComponentProcessor::class, PageMutationFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_MYPAGECOUNT);
        }

        return parent::getFilterSubcomponent($component);
    }
}
