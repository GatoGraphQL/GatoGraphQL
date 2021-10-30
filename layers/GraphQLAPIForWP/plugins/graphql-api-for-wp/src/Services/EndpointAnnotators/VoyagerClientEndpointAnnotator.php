<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointVoyagerBlock;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

class VoyagerClientEndpointAnnotator extends AbstractClientEndpointAnnotator implements CustomEndpointAnnotatorServiceTagInterface
{
    private ?EndpointVoyagerBlock $endpointVoyagerBlock = null;

    final public function setEndpointVoyagerBlock(EndpointVoyagerBlock $endpointVoyagerBlock): void
    {
        $this->endpointVoyagerBlock = $endpointVoyagerBlock;
    }
    final protected function getEndpointVoyagerBlock(): EndpointVoyagerBlock
    {
        return $this->endpointVoyagerBlock ??= $this->instanceManager->getInstance(EndpointVoyagerBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS;
    }

    /**
     * Add actions to the CPT list
     * @param array<string, string> $actions
     */
    public function addCustomPostTypeTableActions(array &$actions, WP_Post $post): void
    {
        // Check the client has not been disabled in the CPT
        if (!$this->isClientEnabled($post)) {
            return;
        }

        if ($permalink = \get_permalink($post->ID)) {
            $title = \_draft_or_post_title();
            $actions['schema'] = sprintf(
                '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                \add_query_arg(
                    RequestParams::VIEW,
                    RequestParams::VIEW_SCHEMA,
                    $permalink
                ),
                /* translators: %s: Post title. */
                \esc_attr(\sprintf(\__('Schema &#8220;%s&#8221;'), $title)),
                __('Interactive schema', 'graphql-api')
            );
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getEndpointVoyagerBlock();
    }
}
