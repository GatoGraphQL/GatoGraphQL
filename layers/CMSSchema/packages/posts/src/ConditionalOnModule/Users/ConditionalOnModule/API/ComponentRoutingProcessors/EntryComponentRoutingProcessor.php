<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Users\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Author's posts
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => [
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST
            ],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        return $ret;
    }
}
