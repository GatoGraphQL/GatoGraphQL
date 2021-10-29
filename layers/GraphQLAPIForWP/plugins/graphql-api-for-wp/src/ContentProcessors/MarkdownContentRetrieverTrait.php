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
     * Make all properties nullable, becase the ModuleRegistry is registered
     * in the SystemContainer, where there are no typeResolvers so it will be null,
     * and in the ApplicationContainer, from where the "Modules" page is resolved
     * and which does have all the typeResolvers.
     * Function `getMarkdownContent` will only be accessed from the Application Container,
     * so the properties will not be null in that situation.
     */
    //#[Required]
    public function autowireMarkdownContentRetrieverTrait(
        ?MarkdownContentParserInterface $markdownContentParser,
    ): void {
        $this->markdownContentParser = $markdownContentParser;
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
