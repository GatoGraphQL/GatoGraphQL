<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use PoP\Root\Services\BasicServiceTrait;
use PoP\QueryParsing\QueryParserInterface;
use stdClass;

class FieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    use BasicServiceTrait;

    // Cache the output from functions
    /**
     * @var array<string, string>
     */
    private array $fieldNamesCache = [];
    /**
     * @var array<string, string|null>
     */
    private array $fieldArgsCache = [];
    /**
     * @var array<string, array<string, int>|null>
     */
    private array $fieldAliasPositionSpansCache = [];

    public final const ALIAS_POSITION_KEY = 'pos';
    public final const ALIAS_LENGTH_KEY = 'length';

    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?QueryParserInterface $queryParser = null;

    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setQueryParser(QueryParserInterface $queryParser): void
    {
        $this->queryParser = $queryParser;
    }
    final protected function getQueryParser(): QueryParserInterface
    {
        return $this->queryParser ??= $this->instanceManager->getInstance(QueryParserInterface::class);
    }

    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool
    {
        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // // If it starts with "%{" and ends with "}%", it is an expression
        // return
        //     is_string($fieldArgValue)
        //     && substr(
        //         $fieldArgValue,
        //         0,
        //         strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING)
        //     ) == QuerySyntax::SYMBOL_EXPRESSION_OPENING
        //     && substr(
        //         $fieldArgValue,
        //         -1 * strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)
        //     ) == QuerySyntax::SYMBOL_EXPRESSION_CLOSING;

        // If it starts with "$_", it is a dynamic variable
        return is_string($fieldArgValue) && str_starts_with($fieldArgValue, '$__');
    }
}
