<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\API\ModuleProcessors;

use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInnerModuleProcessor;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;

class CommentRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS = 'dataload-relationalfields-comments';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS],
        );
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return CommentTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS:
                return [CommentFilterInnerModuleProcessor::class, CommentFilterInnerModuleProcessor::MODULE_FILTERINNER_COMMENTS];
        }

        return parent::getFilterSubmodule($module);
    }
}
