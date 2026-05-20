<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PHPUnit\Framework\TestCase;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Parser;

class CachedParsedASTTest extends TestCase
{
    private const QUERY = <<<'GQL'
        query GetPost($id: ID!, $lang: String) {
            post(by: {id: $id}) {
                id
                title
                author {
                    name
                    posts(first: 5) {
                        ... on Post {
                            title
                        }
                    }
                }
                tags
                meta(keys: ["a", "b"])
            }
        }

        mutation Touch($id: ID!) {
            updatePost(input: {id: $id, title: "new"}) {
                id
            }
        }

        fragment PostShort on Post {
            id
            title
        }
        GQL;

    private function parseSampleDocument(): Document
    {
        return (new Parser())->parse(self::QUERY);
    }

    public function testRoundTripPreservesDocumentStructure(): void
    {
        $document = $this->parseSampleDocument();
        $entry = new CachedParsedAST($document, []);

        /** @var CachedParsedAST $restored */
        $restored = unserialize(serialize($entry));

        $this->assertInstanceOf(CachedParsedAST::class, $restored);
        $this->assertInstanceOf(Document::class, $restored->document);

        $this->assertCount(2, $restored->document->getOperations());
        $this->assertCount(1, $restored->document->getFragments());

        $operations = $restored->document->getOperations();
        $this->assertSame('GetPost', $operations[0]->getName());
        $this->assertSame('Touch', $operations[1]->getName());

        $variables = $operations[0]->getVariables();
        $this->assertCount(2, $variables);
        $this->assertSame('id', $variables[0]->getName());
        $this->assertSame('ID', $variables[0]->getTypeName());
        $this->assertSame('lang', $variables[1]->getName());
        $this->assertSame('String', $variables[1]->getTypeName());

        $fragments = $restored->document->getFragments();
        $this->assertSame('PostShort', $fragments[0]->getName());
        $this->assertSame('Post', $fragments[0]->getModel());
    }

    public function testRoundTripProducesFreshObjectGraph(): void
    {
        $document = $this->parseSampleDocument();
        $entry = new CachedParsedAST($document, []);

        /** @var CachedParsedAST $restored */
        $restored = unserialize(serialize($entry));

        $this->assertNotSame($document, $restored->document);
        $this->assertNotSame(
            $document->getOperations()[0],
            $restored->document->getOperations()[0]
        );
    }

    public function testRoundTripPreservesIntraGraphObjectIdentity(): void
    {
        $document = $this->parseSampleDocument();

        $firstOperation = $document->getOperations()[0];
        $referencedField = $firstOperation->getFieldsOrFragmentBonds()[0];
        $this->assertInstanceOf(FieldInterface::class, $referencedField);

        $entry = new CachedParsedAST($document, [$referencedField]);

        /** @var CachedParsedAST $restored */
        $restored = unserialize(serialize($entry));

        $this->assertCount(1, $restored->objectResolvedFieldValueReferencedFields);

        $restoredFirstOperation = $restored->document->getOperations()[0];
        $restoredFirstField = $restoredFirstOperation->getFieldsOrFragmentBonds()[0];
        $restoredReferencedField = $restored->objectResolvedFieldValueReferencedFields[0];

        $this->assertSame($restoredFirstField, $restoredReferencedField);
    }

    public function testRestoredDocumentSupportsAncestorTraversal(): void
    {
        $document = $this->parseSampleDocument();
        $entry = new CachedParsedAST($document, []);

        /** @var CachedParsedAST $restored */
        $restored = unserialize(serialize($entry));

        $ancestors = $restored->document->getASTNodeAncestors();
        $this->assertGreaterThan(0, count($ancestors));
    }
}
