<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

abstract class AbstractRequestEndpointWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    /**
     * @return array<string,string>
     */
    abstract protected static function getEndpointNamePaths(): array;

    /**
     * Execute a GraphQL query against each of the endpoints
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        $query = static::getGraphQLQuery();
        $expectedResponseBodyWithoutLimit = static::getGraphQLExpectedResponseWithoutLimit();
        $expectedResponseBodyWithLimit = static::getGraphQLExpectedResponseWithLimit();
        $expectedResponseBodyEmptyQuery = static::getGraphQLExpectedResponseEmptyQuery();
        $endpoints = static::getEndpointNamePaths();
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

    protected static function getGraphQLQuery(): string
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

    protected static function getGraphQLExpectedResponseWithoutLimit(): string
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
                            "excerpt": "This is a paragraph block. Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual capital and client-centric markets. Image Block (Standard)",
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
                            "excerpt": "Image Block (Wide) Image Block (Full width) Cover Image Block Gallery Block",
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
                                    "name": "GraphQL"
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

    protected static function getGraphQLExpectedResponseWithLimit(): string
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
                            "excerpt": "This is a paragraph block. Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual capital and client-centric markets. Image Block (Standard)",
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

    protected static function getGraphQLExpectedResponseEmptyQuery(): string
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
