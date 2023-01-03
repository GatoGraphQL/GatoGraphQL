<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

class RequestEndpointWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
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
            $entries[$dataName . '-with-params'] = [
                'application/json',
                $expectedResponseBodyWithLimit,
                $endpoint,
                [],
                $query,
                [
                    'limit' => 2,
                ],
            ];
            $entries[$dataName . '-empty-query'] = [
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
                posts(
                    pagination: {
                        limit: \$limit
                    }
                    sort: {
                        by: ID
                        order: ASC
                    }
                ) {
                    date
                    excerpt
                    slug
                    title
                    tags {
                        name
                    }
                    author {
                        displayName
                        slug
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
                            "date": "2020-04-17T13:06:58+00:00",
                            "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
                            "slug": "hello-world",
                            "title": "Hello world!",
                            "tags": [],
                            "author": {
                                "displayName": "Blogger Davenport",
                                "slug": "blogger",
                                "id": 2
                            }
                        },
                        {
                            "date": "2020-12-12T03:56:13+00:00",
                            "excerpt": "Heading Block (H2) You are looking at one. (H3) Subhead Block Paragraph Block This is a paragraph block.\u00c2 Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual&hellip; <a class=\"more-link\" href=\"https:\/\/graphql-api.lndo.site\/http-caching-improves-performance\/\">Continue reading <span class=\"screen-reader-text\">HTTP caching improves performance<\/span><\/a>",
                            "slug": "released-v0-6-check-it-out",
                            "title": "Released v0.6, check it out",
                            "tags": [
                                {
                                    "name": "release"
                                },
                                {
                                    "name": "plugin"
                                }
                            ],
                            "author": {
                                "displayName": "Subscriber Bennett",
                                "slug": "subscriber",
                                "id": 3
                            }
                        },
                        {
                            "date": "2020-12-12T03:59:07+00:00",
                            "excerpt": "Image Block (Wide width) Image Block (Full width) Cover Image Block Gallery Block",
                            "slug": "working-on-flat-chain-syntax-next",
                            "title": "Working on flat chain syntax next",
                            "tags": [
                                {
                                    "name": "wordpress"
                                },
                                {
                                    "name": "plugin"
                                },
                                {
                                    "name": "graphql"
                                },
                                {
                                    "name": "features"
                                }
                            ],
                            "author": {
                                "displayName": "Contributor Johnson",
                                "slug": "contributor",
                                "id": 4
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
                            "date": "2020-04-17T13:06:58+00:00",
                            "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
                            "slug": "hello-world",
                            "title": "Hello world!",
                            "tags": [],
                            "author": {
                                "displayName": "Blogger Davenport",
                                "slug": "blogger",
                                "id": 2
                            }
                        },
                        {
                            "date": "2020-12-12T03:56:13+00:00",
                            "excerpt": "Heading Block (H2) You are looking at one. (H3) Subhead Block Paragraph Block This is a paragraph block.\u00c2 Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual&hellip; <a class=\"more-link\" href=\"https:\/\/graphql-api.lndo.site\/http-caching-improves-performance\/\">Continue reading <span class=\"screen-reader-text\">HTTP caching improves performance<\/span><\/a>",
                            "slug": "released-v0-6-check-it-out",
                            "title": "Released v0.6, check it out",
                            "tags": [
                                {
                                    "name": "release"
                                },
                                {
                                    "name": "plugin"
                                }
                            ],
                            "author": {
                                "displayName": "Subscriber Bennett",
                                "slug": "subscriber",
                                "id": 3
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
                        "message": "The document is empty",
                        "extensions": {
                            "code": "gql@6.1[c]",
                            "specifiedBy": "https:\/\/spec.graphql.org\/draft\/#sec-Executing-Requests"
                        }
                    }
                ]
            }
        JSON;
    }
}
