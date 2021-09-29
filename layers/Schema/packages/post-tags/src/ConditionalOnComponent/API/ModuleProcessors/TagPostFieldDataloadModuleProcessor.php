<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Posts\ModuleProcessors\PostFilterInputContainerModuleProcessor;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use Symfony\Contracts\Service\Attribute\Required;

class TagPostFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST = 'dataload-relationalfields-tagpostlist';
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected ListQueryInputOutputHandler $listQueryInputOutputHandler;

    #[Required]
    public function autowireTagPostFieldDataloadModuleProcessor(
        PostObjectTypeResolver $postObjectTypeResolver,
        ListQueryInputOutputHandler $listQueryInputOutputHandler,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return $this->postObjectTypeResolver;
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return $this->listQueryInputOutputHandler;
        }

        return parent::getQueryInputOutputHandler($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                $vars = ApplicationState::getVars();
                $ret['tag-ids'] = [$vars['routing-state']['queried-object-id']];
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST:
                return [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTS];
        }

        return parent::getFilterSubmodule($module);
    }
}
