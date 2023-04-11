<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
use GraphQLAPI\GraphQLAPI\Server\GraphQLServerFactory;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

// @todo Do not commit this!!!!
class TestingMustDeleteTemplateHookSet extends AbstractHookSet
{
    public function isServiceEnabled(): bool
    {
        // return true;
        return false;
    }

    protected function init(): void
    {
        App::addAction(
            // 'wp_loaded', // admin
            // 'rest_api_init', // REST
            'wp', // anything else
            // 'shutdown',
            function (): void {
                try {
                    $graphQLServer = GraphQLServerFactory::getInstance();
                    $response = $graphQLServer->execute(
                        <<<GRAPHQL
                            mutation {
                                createPost(input:{
                                    title: "songa",
                                    status: trash
                                }) {
                                    status
                                    postID
                                }
                                updatePost(input:{
                                    id: 88888,
                                    title: "ponga"
                                }) {
                                    status
                                    postID
                                }
                            }
                        GRAPHQL,
                        // <<<GRAPHQL
                        //     {
                        //         id
                        //         _echo(value: "INSIDE HOOK!!")
                        //         siteURL: optionValue(name: "siteurl")
                        //     }
                        // GRAPHQL,
                    );
                    /** @var string */
                    $content = $response->getContent();
                    _e(
                        // PHP_EOL . PHP_EOL . "GraphQLServer:" . PHP_EOL .
                        $content
                        // . PHP_EOL . PHP_EOL
                    );
                    die;
                } catch (GraphQLServerNotReadyException $e) {
                    die($e->getMessage());
                }
            },
            1950
        );
    }
}
