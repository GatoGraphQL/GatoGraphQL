<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PHPUnit\Framework\TestCase;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;

class DocumentTest extends TestCase
{
    public function testValidationWorks()
    {
        $parser = new Parser();
        
        // Validate that there are no errors <= no Exception is thrown
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              factions(names:$names_0, test: $query) {
                id,
                ...F2
              }
            }
            fragment F1 on Faction {
              id,
              factionId
            }
            fragment F2 on Faction {
              id,
              factionId,
              name,
              _shipsDRnzJ:ships(first:10) {
                edges {
                  node {
                    id,
                    ...F0
                  },
                  cursor
                },
                pageInfo {
                  hasNextPage,
                  hasPreviousPage
                }
              },
              ...F1
            }
        ');
        $document->validate();
        $this->assertTrue(true);
    }

    public function testFragmentNotUsed()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              factions(names:$names_0, test: $query) {
                id,
                ...F2
              }
            }
            fragment F0 on Ship {
              id,
              name
            }
            fragment F1 on Faction {
              id,
              factionId
            }
            fragment F2 on Faction {
              id,
              factionId,
              name,
              _shipsDRnzJ:ships(first:10) {
                edges {
                  node {
                    id,
                    ...F0
                  },
                  cursor
                },
                pageInfo {
                  hasNextPage,
                  hasPreviousPage
                }
              },
              ...F1
            }
        ');
        $document->validate();
    }

    public function testFragmentMissing()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              factions(names:$names_0, test: $query) {
                id,
                ...F2
              }
            }
        ');
        $document->validate();
    }

    public function testVariableNotUsed()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String, $notUsedVar: Boolean) {
              factions(names:$names_0, test: $query) {
                id
              }
            }
        ');
        $document->validate();
    }

    public function testVariableMissing()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              factions(names:$names_0, test: $query, someOther: $missingVar) {
                id
              }
            }
        ');
        $document->validate();
    }

    public function testVariableMissingInDirective()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              id
              factions(names:$names_0, test: $query) @include(if: $missingVar) {
                id
              }
            }
        ');
        $document->validate();
    }

    public function testNoOperationsDefined()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            fragment F0 on Ship {
                id,
                name
            }
        ');
        $document->validate();
    }

    public function testDuplicateOperationName()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query SomeQuery {
                users {
                    id
                }
            }

            query SomeQuery {
                posts {
                    title
                }
            }
        ');
        $document->validate();
    }
}
