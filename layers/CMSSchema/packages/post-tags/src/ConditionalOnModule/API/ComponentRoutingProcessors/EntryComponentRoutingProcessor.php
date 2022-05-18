<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\API\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;
use PoPCMSSchema\PostTags\Module;
use PoPCMSSchema\PostTags\ModuleConfiguration;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
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
    public function getStatePropertiesToSelectComponentByNature(): array
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
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routemodules = array(
            $moduleConfiguration->getPostTagsRoute() => [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getModule(PostsModule::class)->getConfiguration();
        $routemodules = array(
            $moduleConfiguration->getPostsRoute() => [TagPostFieldDataloadModuleProcessor::class, TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
        foreach ($routemodules as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $component,
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
