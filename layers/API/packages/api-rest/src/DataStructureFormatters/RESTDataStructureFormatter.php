<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\GraphQLParser\Exception\AbstractASTNodeException;
use PoP\GraphQLParser\Exception\Parser\AbstractParserException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private ?EngineInterface $engine = null;
    private ?GraphQLParserHelperServiceInterface $graphQLParserHelperService = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }
    final public function setGraphQLParserHelperService(GraphQLParserHelperServiceInterface $graphQLParserHelperService): void
    {
        $this->graphQLParserHelperService = $graphQLParserHelperService;
    }
    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        /** @var GraphQLParserHelperServiceInterface */
        return $this->graphQLParserHelperService ??= $this->instanceManager->getInstance(GraphQLParserHelperServiceInterface::class);
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
            $graphQLQueryParsingPayload = $this->getGraphQLParserHelperService()->parseGraphQLQuery(
                $query,
                $variables,
                null,
            );
            $executableDocument = $graphQLQueryParsingPayload->executableDocument;
            $executableDocument->validateAndInitialize();
        } catch (AbstractASTNodeException $e) {
            return [];
        } catch (AbstractParserException $e) {
            return [];
        }
        return $this->getFieldsFromExecutableDocument($executableDocument);
    }
}
