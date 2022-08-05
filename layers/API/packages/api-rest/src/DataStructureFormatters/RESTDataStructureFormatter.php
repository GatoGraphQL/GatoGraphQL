<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPAPI\API\StaticHelpers\GraphQLParserHelpers;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private ?EngineInterface $engine = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
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
        $variables = App::getState('variables');

        try {
            $graphQLQueryParsingPayload = GraphQLParserHelpers::parseGraphQLQuery(
                $query,
                $variables,
                null,
            );
            $executableDocument = $graphQLQueryParsingPayload->executableDocument;
            $executableDocument->validateAndInitialize();
        } catch (SyntaxErrorException | AbstractQueryException $e) {
            return [];
        }
        return $this->getFieldsFromExecutableDocument($executableDocument);
    }
}
