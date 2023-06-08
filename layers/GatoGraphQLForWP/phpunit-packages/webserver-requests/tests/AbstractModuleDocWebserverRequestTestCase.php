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
    final public static function providePageEntries(): array
    {
        return static::provideModuleDocEntries();
    }

    /**
     * @return array<string,string[]>
     */
    protected static function provideModuleDocEntries(): array
    {
        $entries = [];
        foreach (static::getModules() as $module) {
            $pos = strrpos($module, '\\');
            $moduleSlug = $pos === false ? $module : substr($module, $pos);
            $entries[$moduleSlug] = [
                static::getModuleEndpoint($module),
            ];
        }
        return $entries;
    }

    /**
     * @return string[]
     */
    abstract protected static function getModules(): array;
    abstract protected static function getModuleEndpoint(string $module): string;
}
