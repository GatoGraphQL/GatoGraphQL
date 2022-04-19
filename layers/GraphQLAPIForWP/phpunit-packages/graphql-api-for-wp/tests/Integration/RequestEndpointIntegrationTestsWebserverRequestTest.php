<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;

class RequestEndpointIntegrationTestsWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    /**
     * Execute a GraphQL query against each of the endpoints
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $query = $this->getGraphQLQuery();
        $expectedResponseBodyWithoutLimit = $this->getGraphQLExpectedResponseWithoutLimit();
        $expectedResponseBodyWithLimit = $this->getGraphQLExpectedResponseWithLimit();
        $expectedResponseBodyEmptyQuery = $this->getGraphQLExpectedResponseEmptyQuery();
        $endpoints = [
            'single-endpoint' => 'graphql/',
            'custom-endpoint' => 'graphql/mobile-app/',
            'custom-endpoint-with-api-hierarchy' => 'graphql/customers/penguin-books/',
        ];
        $entries = [];
        foreach ($endpoints as $dataName => $endpoint) {
            $entries[$dataName] = [
                'application/json',
                $expectedResponseBodyWithoutLimit,
                $endpoint,
                [],
                $query,
            ];
            $entries[$dataName.'-with-params'] = [
                'application/json',
                $expectedResponseBodyWithLimit,
                $endpoint,
                [],
                $query,
                [
                    'limit' => 2,
                ],
            ];
            $entries[$dataName.'-empty-query'] = [
                'application/json',
                $expectedResponseBodyEmptyQuery,
                $endpoint,
                [],
                '',
            ];
        }
        return $entries;
    }

    protected function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            query MyQuery(\$limit: Int = 3) {
                posts(pagination: {
                    limit: \$limit
                }) {
                    date
                    excerpt
                    url
                    title
                    tags {
                        name
                    }
                    author {
                        displayName
                        url
                        id
                    }
                }
            }        
        GRAPHQL;
    }

    protected function getGraphQLExpectedResponseWithoutLimit(): string
    {
        return <<<JSON
            {
                "data": {
                    "posts": [
                        {
                            "date": "2022-04-17T13:06:58+00:00",
                            "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
                            "url": "http://graphql-api.lndo.site/hello-world/",
                            "title": "Hello world!",
                            "tags": [],
                            "author": {
                                "displayName": "admin",
                                "url": "http://graphql-api.lndo.site/author/admin/",
                                "id": 1
                            }
                        },
                        {
                            "date": "2020-12-12T04:08:47+00:00",
                            "excerpt": "Categories Block Latest Posts Block",
                            "url": "http://graphql-api.lndo.site/http-caching-improves-performance/",
                            "title": "HTTP caching improves performance",
                            "tags": [],
                            "author": {
                                "displayName": "admin",
                                "url": "http://graphql-api.lndo.site/author/admin/",
                                "id": 1
                            }
                        },
                        {
                            "date": "2020-12-12T04:06:52+00:00",
                            "excerpt": "Verse Block Write poetry and other literary expressions honoring all spaces and line-breaks. Table Block Row 1 Column 1 Row 1 Column 2 Row 2 Column 1 Row 2 Column 2 Row 3 Column 1 Row 3 Column 2 Separator Block Spacer Block",
                            "url": "http://graphql-api.lndo.site/public-or-private-api-mode-for-extra-security/",
                            "title": "Public or Private API mode, for extra security",
                            "tags": [
                                {
                                    "name": "security"
                                },
                                {
                                    "name": "features"
                                }
                            ],
                            "author": {
                                "displayName": "admin",
                                "url": "http://graphql-api.lndo.site/author/admin/",
                                "id": 1
                            }
                        }
                    ]
                }
            }
        JSON;
    }

    protected function getGraphQLExpectedResponseWithLimit(): string
    {
        return <<<JSON
            {
                "data": {
                    "posts": [
                        {
                            "date": "2022-04-17T13:06:58+00:00",
                            "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
                            "url": "http://graphql-api.lndo.site/hello-world/",
                            "title": "Hello world!",
                            "tags": [],
                            "author": {
                                "displayName": "admin",
                                "url": "http://graphql-api.lndo.site/author/admin/",
                                "id": 1
                            }
                        },
                        {
                            "date": "2020-12-12T04:08:47+00:00",
                            "excerpt": "Categories Block Latest Posts Block",
                            "url": "http://graphql-api.lndo.site/http-caching-improves-performance/",
                            "title": "HTTP caching improves performance",
                            "tags": [],
                            "author": {
                                "displayName": "admin",
                                "url": "http://graphql-api.lndo.site/author/admin/",
                                "id": 1
                            }
                        }
                    ]
                }
            }
        JSON;
    }

    protected function getGraphQLExpectedResponseEmptyQuery(): string
    {
        return <<<JSON
            {
                "errors": [
                    {
                        "message": "The query has not been provided",
                        "locations": [
                            {
                                "line": 1,
                                "column": 1
                            }
                        ],
                        "extensions": {
                            "code": "gql-6.1.c",
                            "specifiedBy": "https:\/\/spec.graphql.org\/draft\/#sec-Executing-Requests"
                        }
                    }
                ]
            }
        JSON;
    }
}
