<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

/**
 * Update a post before/after executing the test.
 *
 * It uses the REST API to update the post before/after executing
 * the test. That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
abstract class AbstractUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use UpdateCustomPostBeforeTestWebserverRequestTestTrait;

    /** @var array<string,mixed> */
    protected array $originalCustomPostData;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Modify the post data before executing the ":enabled" test
         */
        $dataName = $this->getDataName();
        if ($this->mustExecuteRESTEndpointToUpdateCustomPost($dataName)) {
            $this->originalCustomPostData = $this->executeRESTEndpointToGetOriginalCustomPostData($dataName);
            $this->executeRESTEndpointToUpdateCustomPost($dataName, $this->getUpdatedCustomPostData($dataName));
        }
    }

    abstract protected function mustExecuteRESTEndpointToUpdateCustomPost(string $dataName): bool;

    protected function tearDown(): void
    {
        /**
         * Revert the post data after executing the ":enabled" test
         */
        $dataName = $this->getDataName();
        if ($this->mustExecuteRESTEndpointToUpdateCustomPost($dataName)) {
            $this->executeRESTEndpointToUpdateCustomPost($dataName, $this->getOriginalCustomPostData($dataName));
        }

        parent::tearDown();
    }

    /**
     * @return array<string,mixed>
     */
    abstract protected function getUpdatedCustomPostData(string $dataName): array;

    /**
     * @return array<string,mixed>
     */
    protected function getOriginalCustomPostData(string $dataName): array
    {
        $originalCustomPostData = [];
        foreach (array_keys($this->getUpdatedCustomPostData($dataName)) as $key) {
            $originalCustomPostData = $this->originalCustomPostData[$key];
        }
        return $originalCustomPostData;
    }

    protected static function getEndpoint(): string
    {
        if (static::useAdminEndpoint()) {
            return static::getAdminEndpoint();
        }
        return 'graphql/';
    }

    protected static function useAdminEndpoint(): bool
    {
        return false;
    }

    protected static function getAdminEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }
}
