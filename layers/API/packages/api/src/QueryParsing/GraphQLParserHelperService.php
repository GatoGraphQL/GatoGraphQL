<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\Parser\LogicErrorParserException;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\App;
use PoP\Root\Services\AbstractBasicService;
use PoPAPI\API\Cache\CacheTypes;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;

class GraphQLParserHelperService extends AbstractBasicService implements GraphQLParserHelperServiceInterface
{
    private ?PersistentCacheInterface $persistentCache = null;

    final protected function getPersistentCache(): PersistentCacheInterface
    {
        if ($this->persistentCache === null) {
            /** @var PersistentCacheInterface */
            $persistentCache = $this->instanceManager->getInstance(PersistentCacheInterface::class);
            $this->persistentCache = $persistentCache;
        }
        return $this->persistentCache;
    }

    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws LogicErrorParserException
     * @param array<string,mixed> $variableValues
     */
    public function parseGraphQLQuery(
        string $query,
        array $variableValues,
        ?string $operationName,
    ): GraphQLQueryParsingPayload {
        [$document, $referencedFields] = $this->getDocumentAndReferencedFields($query);
        $executableDocument = new ExecutableDocument(
            $document,
            new Context($operationName, $variableValues)
        );
        return new GraphQLQueryParsingPayload(
            $executableDocument,
            $referencedFields,
        );
    }

    /**
     * Parses (or restores from persistent cache) the Document AST and the list
     * of ObjectResolvedFieldValueReference fields. The two are bundled together
     * because PHP's `serialize()` only preserves intra-graph object identity
     * within a single call — caching them separately would break the invariant
     * that `$referencedFields` entries are the same objects as nodes inside
     * `$document`.
     *
     * @return array{0: Document, 1: \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface[]}
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws LogicErrorParserException
     */
    protected function getDocumentAndReferencedFields(string $query): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $useCache = $moduleConfiguration->useParsedASTCache();

        if ($useCache) {
            $persistentCache = $this->getPersistentCache();
            $cacheKey = hash('md5', $query);
            if ($persistentCache->hasCache($cacheKey, CacheTypes::PARSED_AST)) {
                /**
                 * Store/retrieve the AST as a serialized string (not the object
                 * graph directly) so that every cache hit yields a *fresh*
                 * unserialized object. The PSR-6 in-memory layer otherwise
                 * returns the same instance to every consumer in this PHP
                 * process — and AST nodes lazy-cache references to per-request
                 * services (e.g. `Document::$dynamicVariableDefinerDirectiveRegistry`,
                 * `AbstractAst::$graphQLQueryStringFormatter`). Sharing those
                 * across requests means request N reads request 1's container.
                 */
                $serialized = $persistentCache->getCache($cacheKey, CacheTypes::PARSED_AST);
                if (is_string($serialized)) {
                    $cached = unserialize($serialized);
                    if ($cached instanceof CachedParsedAST) {
                        return [$cached->document, $cached->objectResolvedFieldValueReferencedFields];
                    }
                }
            }
        }

        $parser = $this->createParser();
        $document = $this->parseQuery($parser, $query);
        $referencedFields = $parser->getObjectResolvedFieldValueReferencedFields();

        if ($useCache) {
            $persistentCache->storeCache(
                $cacheKey,
                CacheTypes::PARSED_AST,
                serialize(new CachedParsedAST($document, $referencedFields)),
            );
        }

        return [$document, $referencedFields];
    }

    protected function createParser(): ParserInterface
    {
        return new Parser();
    }

    protected function parseQuery(ParserInterface $parser, string $query): Document
    {
        return $parser->parse($query);
    }
}
