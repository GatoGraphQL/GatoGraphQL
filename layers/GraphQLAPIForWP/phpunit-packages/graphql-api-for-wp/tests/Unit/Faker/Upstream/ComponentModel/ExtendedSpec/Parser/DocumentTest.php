<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Upstream\ComponentModel\ExtendedSpec\Parser;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;

class DocumentTest extends AbstractTestCase
{
    /**
     * @return string[]
     */
    protected static function getModuleClassesToInitialize(): array
    {
        return [
            ...parent::getModuleClassesToInitialize(),
            ...[
                \GraphQLByPoP\ExportDirective\Module::class,
            ]
        ];
    }

    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION] = true;
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES] = true;
        return $moduleClassConfiguration;
    }

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    public function testSharedNameBetweenVariableAndStaticVariable()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLExtendedSpecErrorFeedbackItemProvider::class, GraphQLExtendedSpecErrorFeedbackItemProvider::E7, ['_id']))->getMessage());
        $parser = $this->getParser();
        $document = $parser->parse('
            query One {
                id @upperCase @export(as: "_id")
            }
            
            query Two($_id: ID!) {
                mirror: echo(value: $_id)
            }
            
            query __ALL { id }
        ');
        $document->validate();
    }
}
