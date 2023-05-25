<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

abstract class AbstractModuleDocWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @dataProvider provideModuleDocEntries
     */
    public function testModuleDocsExist(
        string $endpoint,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        $response = $client->get(
            $endpointURL,
            $options
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringStartsWith('text/html', $response->getHeaderLine('content-type'));
        
        /**
         * Assert that the response does NOT contain the
         * "There is no documentation for this module" error message
         */
        $responseBody = $response->getBody()->__toString();
        $this->assertStringNotContainsString(
            $this->getModuleDocErrorMessage(),
            $responseBody
        );
    }

    /**
     * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/ModuleResolvers/HasMarkdownDocumentationModuleResolverTrait.php function `getDocumentation`
     */
    protected function getModuleDocErrorMessage(): string
    {
        return 'Oops, the documentation for this module is not available';
    }

    /**
     * @return array<string,string[]>
     */
    protected function provideModuleDocEntries(): array
    {
        $entries = [];
        foreach ($this->getModules() as $module) {
            $moduleSlug = substr(
                $module,
                strrpos($module, '\\')
            );
            $entries[$moduleSlug] = [
                $this->getModuleEndpoint($module),
            ];
        }
        return $entries;
    }
    
    /**
     * @return string[]
     */
    abstract protected function getModules(): array;
    abstract protected function getModuleEndpoint(string $module): string;
}
