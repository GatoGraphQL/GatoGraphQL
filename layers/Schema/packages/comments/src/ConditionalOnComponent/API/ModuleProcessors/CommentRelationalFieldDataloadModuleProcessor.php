<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\API\ModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

class CommentRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS = 'dataload-relationalfields-comments';
    protected CommentObjectTypeResolver $commentObjectTypeResolver;
    protected ListQueryInputOutputHandler $listQueryInputOutputHandler;

    #[Required]
    public function autowireCommentRelationalFieldDataloadModuleProcessor(
        CommentObjectTypeResolver $commentObjectTypeResolver,
        ListQueryInputOutputHandler $listQueryInputOutputHandler,
    ): void {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS],
        );
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return $this->commentObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return $this->listQueryInputOutputHandler;
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTS];
        }

        return parent::getFilterSubmodule($module);
    }
}
