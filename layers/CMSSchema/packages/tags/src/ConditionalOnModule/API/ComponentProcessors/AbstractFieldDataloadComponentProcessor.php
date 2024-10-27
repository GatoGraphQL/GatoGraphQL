<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Tags\ComponentProcessors\TagFilterInputContainerComponentProcessor;

abstract class AbstractFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAG = 'dataload-relationalfields-tag';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST = 'dataload-relationalfields-taglist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCOUNT = 'dataload-relationalfields-tagcount';

    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
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
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAG,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCOUNT,
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAG:
                return $this->getQueriedDBObjectID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAG:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getPostTagObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return new Component(TagFilterInputContainerComponentProcessor::class, TagFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_TAGS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCOUNT:
                return new Component(TagFilterInputContainerComponentProcessor::class, TagFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_TAGCOUNT);
        }

        return parent::getFilterSubcomponent($component);
    }
}
