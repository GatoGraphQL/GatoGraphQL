<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServerHelpers;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
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
        $variableValues = App::getState('variables');

        try {
            $executableDocument = GraphQLServerHelpers::parseGraphQLQuery(
                $query,
                $variableValues,
                null,
            );
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            return [];
        }
        return $this->getFieldsFromExecutableDocument($executableDocument);
    }
}
