<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractClientEndpointExecuter extends AbstractEndpointExecuter
{
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    //#[Required]
    final public function autowireAbstractClientEndpointExecuter(
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ): void {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLCustomEndpointCustomPostType();
    }

    public function executeEndpoint(): void
    {
        // Print the HTML from the client, and that's it
        echo $this->getClient()->getClientHTML();
        die;
    }

    abstract protected function getClient(): AbstractClient;

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check the client has not been disabled in the CPT
        global $post;
        if (!$this->getClientEndpointAnnotator()->isClientEnabled($post)) {
            return false;
        }

        return true;
    }

    abstract protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface;
}
