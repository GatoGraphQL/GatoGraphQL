<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * In a linked git worktree (where `.git` is a file pointing to a "worktrees"
 * folder, also when this repo is a submodule within one), the devserver
 * serving the canonical domain runs the main checkout's code, so the
 * integration tests must be routed to the worktree's own devserver.
 */
$gitFile = __DIR__ . '/../.git';
if (
    is_file($gitFile)
    && preg_match('#^gitdir: .*/worktrees/#m', (string) file_get_contents($gitFile)) === 1
    && getenv('INTEGRATION_TESTS_WEBSERVER_ROUTE_REQUIRED') === false
) {
    putenv('INTEGRATION_TESTS_WEBSERVER_ROUTE_REQUIRED=1');
}
