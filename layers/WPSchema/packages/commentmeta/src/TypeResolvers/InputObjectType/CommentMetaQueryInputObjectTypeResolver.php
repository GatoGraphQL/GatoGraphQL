<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\CommentMeta\Module;
use PoPCMSSchema\CommentMeta\ModuleConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class CommentMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getCommentMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getCommentMetaBehavior();
    }
}
