<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;

class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST = 'dataload-relationalfields-authorcustompostlist';

    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        if ($this->customPostUnionTypeResolver === null) {
            /** @var CustomPostUnionTypeResolver */
            $customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
            $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        }
        return $this->customPostUnionTypeResolver;
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
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST,
        );
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return $this->getCustomPostUnionTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    protected function getMutableonrequestDataloadQueryArgs(Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                $ret['authors'] = [
                    App::getState(['routing', 'queried-object-id']),
                ];
                break;
        }

        return $ret;
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTS);
        }

        return parent::getFilterSubcomponent($component);
    }
}
