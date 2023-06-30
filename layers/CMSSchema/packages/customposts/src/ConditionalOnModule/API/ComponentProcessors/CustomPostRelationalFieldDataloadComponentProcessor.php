<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class CustomPostRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST = 'dataload-relationalfields-singlecustompost';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST = 'dataload-relationalfields-unioncustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-unioncustompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST = 'dataload-relationalfields-adminunioncustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-adminunioncustompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'dataload-relationalfields-custompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT = 'dataload-relationalfields-custompostcount';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST = 'dataload-relationalfields-admincustompostlist';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT = 'dataload-relationalfields-admincustompostcount';

    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        if ($this->customPostUnionTypeResolver === null) {
            /** @var CustomPostUnionTypeResolver */
            $customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
            $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        }
        return $this->customPostUnionTypeResolver;
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
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT,
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
                return $this->getQueriedDBObjectID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getCustomPostUnionTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
                );
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT:
                return new Component(
                    CustomPostFilterInputContainerComponentProcessor::class,
                    CustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT
                );
        }

        return parent::getFilterSubcomponent($component);
    }
}
