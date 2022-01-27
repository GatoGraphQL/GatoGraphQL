<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PHPUnit\Framework\TestCase;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;

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
        $this->assertTrue(true);
    }

    public function testMissingFragmentReferencedByFragment()
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
            fragment F2 on Faction {
              ...F1
            }
        ');
        $document->validate();
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

    public function testVariableMissingInInputObject()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query {
              id
              posts(filter: { search: $search }) {
                id
              }
            }
        ');
        $document->validate();
    }

    public function testVariableMissingInInputList()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query {
              id
              posts(ids: [$id]) {
                id
              }
            }
        ');
        $document->validate();
    }

    public function testVariableInFragment()
    {
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute($includeName: Boolean) {
              id
              factions {
                ...F2
              }
            }

            fragment F2 on Faction {
              id
              name @include(if: $includeName)
            }
        ');
        $document->validate();
        $this->assertTrue(true);
    }

    public function testVariableMissingInFragment()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query StarWarsAppHomeRoute {
              id
              factions {
                ...F2
              }
            }

            fragment F2 on Faction {
              id
              name @include(if: $missingVar)
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

    public function testUniqueOperationName()
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

    public function testUniqueOperationNameAcrossOps()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query SomeQuery {
                users {
                    id
                }
            }

            mutation SomeQuery {
                posts {
                    title
                }
            }
        ');
        $document->validate();
    }

    public function testUniqueVariableName()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query SomeQuery($someVar: String, $someVar: Boolean) {
                users {
                    id
                    name(format: $someVar)
                }
            }
        ');
        $document->validate();
    }

    public function testNonEmptyOperationName()
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse('
            query SomeQuery {
                users {
                    id
                }
            }

            mutation {
                posts {
                    title
                }
            }
        ');
        $document->validate();
    }

    /**
     * @dataProvider duplicateArgumentQueryProvider
     */
    public function testDuplicateArgument($query)
    {
        $this->expectException(InvalidRequestException::class);
        $parser = new Parser();
        $document = $parser->parse($query);
        $document->validate();
    }

    public function duplicateArgumentQueryProvider(): array
    {
        return [
            ['
                {
                    posts {
                        id
                        date(format: "d/m/Y", format: "d, m, Y")
                    }
                }
            '],
            ['
                {
                    posts(limit: 3, limit: 5) {
                        id
                    }
                }
            '],
            ['
                {
                    posts {
                        ...Frag
                    }
                }

                fragment Frag on Post {
                    date(format: "d/m/Y", format: "d, m, Y")
                }
            '],
            ['
                {
                    posts {
                        ... on Post {
                            date(format: "d/m/Y", format: "d, m, Y")
                        }
                    }
                }
            '],
            ['
                {
                    posts {
                        ...Frag
                    }
                }

                fragment Frag on Post {
                    ... on Post {
                        date(format: "d/m/Y", format: "d, m, Y")
                    }
                }
            '],
            ['
                {
                    posts @include(if: true, if: false) {
                        id
                    }
                }
            '],
        ];
    }
}
