<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use PoP\Root\App;
use InvalidArgumentException;
use PoPSchema\CommentMeta\Component;
use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;

abstract class AbstractCommentMetaTypeAPI extends AbstractMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException
     */
    final public function getCommentMeta(string | int $commentID, string $key, bool $single = false, array $options = []): mixed
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetCommentMeta($commentID, $key, $single);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
