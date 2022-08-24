<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST = 'dataload-relationalfields-singlepost';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST = 'dataload-relationalfields-postlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT = 'dataload-relationalfields-postcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST = 'dataload-relationalfields-adminpostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT = 'dataload-relationalfields-adminpostcount';

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        /** @var ListQueryInputOutputHandler */
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT,
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
                return $this->getQueriedDBObjectID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT);
        }

        return parent::getFilterSubcomponent($component);
    }
}
