<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockHelpers;
use WP_Post;

abstract class AbstractClientEndpointAnnotator extends AbstractEndpointAnnotator implements ClientEndpointAnnotatorInterface
{
    private ?BlockHelpers $blockHelpers = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        if ($this->blockHelpers === null) {
            /** @var BlockHelpers */
            $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
            $this->blockHelpers = $blockHelpers;
        }
        return $this->blockHelpers;
    }

    /**
     * Read the options block and check the value of attribute "isEnabled"
     */
    public function isClientEnabled(WP_Post|int $postOrID): bool
    {
        // Check the endpoint in the post is not disabled
        if (!$this->getCustomPostType()->isEndpointEnabled($postOrID)) {
            return false;
        }

        // If there was no options block, something went wrong in the post content
        $default = true;
        $optionsBlockDataItem = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getBlock()
        );
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // The default value is not saved in the DB in Gutenberg!
        $attribute = $this->getIsEnabledAttributeName();
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }

    abstract protected function getBlock(): BlockInterface;

    protected function getIsEnabledAttributeName(): string
    {
        return BlockAttributeNames::IS_ENABLED;
    }
}
