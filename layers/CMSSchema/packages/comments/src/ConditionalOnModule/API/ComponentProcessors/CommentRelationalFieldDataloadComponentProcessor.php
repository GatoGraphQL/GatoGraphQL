<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\API\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

class CommentRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS = 'dataload-relationalfields-comments';

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?ListQueryInputOutputHandler $listQueryInputOutputHandler = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setListQueryInputOutputHandler(ListQueryInputOutputHandler $listQueryInputOutputHandler): void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    final protected function getListQueryInputOutputHandler(): ListQueryInputOutputHandler
    {
        return $this->listQueryInputOutputHandler ??= $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS],
        );
    }

    public function getRelationalTypeResolver(array $componentVariation): ?RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return $this->getCommentObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return $this->getListQueryInputOutputHandler();
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return [CommentFilterInputContainerComponentProcessor::class, CommentFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }
}
