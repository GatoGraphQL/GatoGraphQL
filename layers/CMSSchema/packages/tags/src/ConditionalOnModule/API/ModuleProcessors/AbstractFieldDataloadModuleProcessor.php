<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ConditionalOnModule\API\ComponentProcessors;

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

    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAG = 'dataload-relationalfields-tag';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST = 'dataload-relationalfields-taglist';
    public final const MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT = 'dataload-relationalfields-tagcount';

    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array | null
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getPostTagObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return [TagFilterInputContainerComponentProcessor::class, TagFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_TAGS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT:
                return [TagFilterInputContainerComponentProcessor::class, TagFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_TAGCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
