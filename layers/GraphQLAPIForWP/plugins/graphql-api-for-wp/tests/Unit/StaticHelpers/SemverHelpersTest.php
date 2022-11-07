<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

use PHPUnit\Framework\TestCase;

class SemverHelpersTest extends TestCase
{
    /**
     * @dataProvider provideRemoveDevMetadataFromPluginVersion
     */
    public function testRemoveDevMetadataFromPluginVersion(
        string $pluginVersion,
        string $expectedPluginVersion,
    ): void {
        $this->assertEquals(
            $expectedPluginVersion,
            SemverHelpers::removeCommitHashFromPluginVersion($pluginVersion),
        );
    }

    /**
     * @return array<array{0:string,1:string}>
     */
    protected function provideRemoveDevMetadataFromPluginVersion(): array
    {
        return [
            ['1.0.0', '1.0.0'],
            ['1.0.0#5843900938', '1.0.0'],
            ['1.0.0.4-RC', '1.0.0.4-RC'],
            ['1.0.0.4-RC1#5843900938', '1.0.0.4-RC1'],
            ['1.0.0.4-dev', '1.0.0.4-dev'],
            ['1.0.0.4-dev#5843900938', '1.0.0.4-dev'],
            ['1.0.0-dev', '1.0.0-dev'],
            ['1.0.0-dev#5843900938', '1.0.0-dev'],
        ];
    }
}
