<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\PostMutations\ComponentProcessors\PostMutationFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTLIST = 'dataload-relationalfields-mypostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT = 'dataload-relationalfields-mypostcount';

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
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
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT,
        );
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return new Component(PostMutationFilterInputContainerComponentProcessor::class, PostMutationFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return new Component(PostMutationFilterInputContainerComponentProcessor::class, PostMutationFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT);
        }

        return parent::getFilterSubcomponent($component);
    }
}
