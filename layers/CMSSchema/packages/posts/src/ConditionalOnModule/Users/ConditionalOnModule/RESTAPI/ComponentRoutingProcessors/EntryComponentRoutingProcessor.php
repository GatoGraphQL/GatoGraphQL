<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ConditionalOnModule\RESTAPI\Hooks\CustomPostHookSet;
use PoPCMSSchema\Users\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractCustomPostRESTEntryComponentRoutingProcessor
{
    /**
     * Remove the author data, added by hook to CustomPosts
     */
    public function getRESTFieldsQuery(): string
    {
        if (is_null($this->restFieldsQuery)) {
            $this->restFieldsQuery = str_replace(
                ',' . CustomPostHookSet::AUTHOR_RESTFIELDS,
                '',
                parent::getRESTFieldsQuery()
            );
        }
        return $this->restFieldsQuery;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();
        // Author's posts
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routemodules = array(
            $moduleConfiguration->getPostsRoute() => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                ],
            ];
        }
        return $ret;
    }
}
