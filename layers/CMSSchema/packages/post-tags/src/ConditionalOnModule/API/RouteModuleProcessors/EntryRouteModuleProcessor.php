<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\API\RouteModuleProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsComponentConfiguration;
use PoPCMSSchema\PostTags\Module;
use PoPCMSSchema\PostTags\ModuleConfiguration;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[TagRequestNature::TAG][] = [
            'module' => [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
            'conditions' => [
                'scheme' => APISchemes::API,
                'routing' => [
                    'taxonomy-name' => $this->getPostTagTypeAPI()->getPostTagTaxonomyName(),
                ],
            ],
        ];
        return $ret;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostTagsRoute() => [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        /** @var PostsComponentConfiguration */
        $componentConfiguration = App::getComponent(PostsModule::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostsRoute() => [TagPostFieldDataloadModuleProcessor::class, TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'routing' => [
                        'taxonomy-name' => $this->getPostTagTypeAPI()->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
