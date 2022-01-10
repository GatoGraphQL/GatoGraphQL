<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

abstract class AbstractLoadingCPTSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * Initialize the configuration if visiting the corresponding CPT
     */
    protected function getCustomPostID(): ?int
    {
        if (\is_singular($this->getCustomPostType())) {
            // Watch out! If accessing $vars it triggers setting ComponentConfiguration vars,
            // but we have not set the hooks yet!
            // For instance for `mustNamespaceTypes()`,
            // to be set in `executeSchemaConfigurationOptionsNamespacing()`
            // Hence, code below was commented, and access the $post from the global variable
            // $vars = ApplicationState::getVars();
            // $postID = $vars['routing']['queried-object-id'];
            global $post;
            return $post->ID;
        }
        return null;
    }

    abstract protected function getCustomPostType(): string;
}
