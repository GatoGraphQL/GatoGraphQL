<?php

declare(strict_types=1);

namespace GraphQLAPI\PHPUnitWPFakerSchema\Standalone;

use Brain\Faker\Providers;
use Faker\Generator;
use GraphQLByPoP\GraphQLServer\Standalone\AbstractFixtureQueryExecutionGraphQLServerTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

use function Brain\faker;
use function Brain\fakerReset;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

/**
 * Integration with BrainFaker
 *
 * @see https://github.com/Brain-WP/BrainFaker
 */
abstract class AbstractFakerFixtureQueryExecutionGraphQLServerTestCase extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    use MockeryPHPUnitIntegration;
    
    protected Generator $faker;
    
    protected Providers $wpFaker;
    
    protected function setUp(): void
    {
        parent::setUp();
        setUp();
        
        $this->faker = faker();
        $this->wpFaker = $this->faker->wp();
    }
    
    protected function tearDown(): void
    {
        fakerReset();
        
        tearDown();
        parent::tearDown();
    }
}
