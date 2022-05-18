<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ComponentProcessors;

use PoP\Root\App;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST = 'dataload-relationalfields-authorpostlist';

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
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST],
        );
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return $this->getPostObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                $ret['authors'] = [
                    App::getState(['routing', 'queried-object-id']),
                ];
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return [PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_POSTS];
        }

        return parent::getFilterSubmodule($component);
    }
}
