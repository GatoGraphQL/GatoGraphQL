<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use InvalidArgumentException;
use Symfony\Contracts\Service\Attribute\Required;

trait MarkdownContentRetrieverTrait
{
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
    ): ?string {
        // Inject the place to look for the documentation
        $this->markdownContentParser->setBaseDir($this->getBaseDir());
        $this->markdownContentParser->setBaseURL($this->getBaseURL());
        try {
            return $this->markdownContentParser->getContent(
                $markdownFilename,
                $relativePathDir,
                $options
            );
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Get the dir where to look for the documentation.
     */
    abstract protected function getBaseDir(): string;

    /**
     * Get the URL where to look for the documentation.
     */
    abstract protected function getBaseURL(): string;
}
