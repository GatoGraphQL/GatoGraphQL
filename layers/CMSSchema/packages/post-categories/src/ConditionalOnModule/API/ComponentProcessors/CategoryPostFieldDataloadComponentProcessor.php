<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnModule\API\ComponentProcessors;

use PoP\Root\App;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class CategoryPostFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST = 'dataload-relationalfields-categorypostlist';

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST],
        );
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                $ret['category-ids'] = [App::getState(['routing', 'queried-object-id'])];
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return [PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_POSTS];
        }

        return parent::getFilterSubmodule($component);
    }
}
