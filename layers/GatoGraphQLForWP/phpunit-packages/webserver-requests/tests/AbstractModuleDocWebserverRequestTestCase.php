<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractModuleDocWebserverRequestTestCase extends AbstractWPAdminPageWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected function executeAsserts(
        ResponseInterface $response,
    ): void {
        parent::executeAsserts($response);

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
    final protected function providePageEntries(): array
    {
        return $this->provideModuleDocEntries();
    }

    /**
     * @return array<string,string[]>
     */
    protected function provideModuleDocEntries(): array
    {
        $entries = [];
        foreach ($this->getModules() as $module) {
            $pos = strrpos($module, '\\');
            $moduleSlug = $pos === false ? $module : substr($module, $pos);
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
