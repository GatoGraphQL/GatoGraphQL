<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private ?EngineInterface $engine = null;
    private ?ParserInterface $parser = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }
    final public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }
    final protected function getParser(): ParserInterface
    {
        return $this->parser ??= $this->instanceManager->getInstance(ParserInterface::class);
    }

    public function getName(): string
    {
        return 'rest';
    }

    /**
     * If provided, get the fields from the entry component atts.
     *
     * @return FieldInterface[]
     */
    protected function getFields(): array
    {
        $entryComponent = $this->getEngine()->getEntryComponent();
        $query = $entryComponent->atts['query'] ?? null;
        if ($query === null || $query === '') {
            return parent::getFields();
        }

        // Parse the GraphQL query
        $variableValues = App::getState('variables');
        $operationName = App::getState('graphql-operation-name');

        try {
            $executableDocument = $this->parseGraphQLQuery(
                $query,
                $variableValues,
                $operationName
            );
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            return [];
        }
        return $this->getFieldsFromExecutableDocument($executableDocument);
    }

    /**
     * @throws SyntaxErrorException
     * @throws InvalidRequestException
     */
    protected function parseGraphQLQuery(
        string $query,
        array $variableValues,
        ?string $operationName,
    ): ExecutableDocument {
        $document = $this->getParser()->parse($query)->setAncestorsInAST();
        /** @var ExecutableDocument */
        $executableDocument = (
            new ExecutableDocument(
                $document,
                new Context($operationName, $variableValues)
            )
        )->validateAndInitialize();
        return $executableDocument;
    }
}
