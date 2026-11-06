#!/usr/bin/env python3
"""Route a Lando app through the shared Lando proxy, without restarting it.

Traefik, the shared Lando proxy, registers the routes of the containers that
are running when it starts, and never those of a container started afterwards
(measured: they answer 404, or 502 when Traefik still holds the routes of the
app's previous container). Restarting the proxy fixes that, but drops the
requests in flight to every other devserver, eg: a test suite running against
another app. Instead, this writes the app's routes to a file in the proxy's
config folder, which Traefik hot-reloads.

The routes are the ones Lando puts in the app containers' Traefik labels,
pointing at the container by name (resolved by Docker on the proxy's network),
so the file holds across restarts and rebuilds. Routers whose name starts with
"slot-" are left out: those are written by whoever runs devservers in worktree
slots, with --write.

Usage:

    python3 route-in-lando-proxy.py [<compose project>]
        Route the app, by default the one of the Lando config in the current
        folder (eg: gatographql).

    <routes> | python3 route-in-lando-proxy.py --write <name>
    python3 route-in-lando-proxy.py --remove <name>
        Write the routes read from stdin to <name>.yml in the proxy's config
        folder, or remove that file.
"""

from __future__ import annotations

import json
import re
import ssl
import subprocess
import sys
import time
import urllib.error
import urllib.request
from pathlib import Path

PROXY_CONTAINER_FILTER = 'landoproxyhyperion5000gandalfedition_proxy'
PROXY_CONFIG_DIR = '/proxy_config'
SLOT_ROUTE_PREFIX = 'slot-'
# Above the routes Traefik may still hold from the app's previous container
# (Lando's labels set no priority, so Traefik ranks them by rule length), and
# below the routes of worktree slots (priority 1000), which take the requests
# carrying their header
ROUTER_PRIORITY = 500
# Traefik reloads the file within a second or two
URL_CHECK_SECONDS = 10


def log(message: str) -> None:
    print(f'>>> {message}', file=sys.stderr)


def die(message: str) -> None:
    print(f'❌ {message}', file=sys.stderr)
    sys.exit(1)


def docker(*args: str, stdin: str | None = None) -> str:
    result = subprocess.run(['docker', *args], input=stdin, capture_output=True, text=True)
    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip())
    return result.stdout


def proxy_container() -> str:
    proxy = docker('ps', '-q', '--filter', f'name={PROXY_CONTAINER_FILTER}').split()
    if not proxy:
        die('The Lando proxy is not running')
    return proxy[0]


def write_routes(name: str, routes: str) -> None:
    """Write to a temp file first (Traefik ignores it, by its extension) and
    then move it, so Traefik never reads half a file"""
    proxy = proxy_container()
    path = f'{PROXY_CONFIG_DIR}/{name}.yml'
    try:
        current = docker('exec', proxy, 'cat', path)
    except RuntimeError:
        current = None
    if current is not None and current.strip() == routes.strip():
        return
    tmp = f'{PROXY_CONFIG_DIR}/.{name}.tmp'
    try:
        docker('exec', '-i', proxy, 'sh', '-c', f"cat > '{tmp}' && mv '{tmp}' '{path}'", stdin=routes.rstrip('\n') + '\n')
    except RuntimeError as error:
        die(f'Could not write {path} in the Lando proxy: {error}')
    log(f'Routed {name} through the Lando proxy ({path})')


def remove_routes(name: str) -> None:
    proxy = proxy_container()
    docker('exec', proxy, 'rm', '-f', f'{PROXY_CONFIG_DIR}/{name}.yml')


def project_from_lando_config() -> str:
    """The app name, as overridden by .lando.local.yml (eg: in a worktree
    slot), with dashes removed, as Docker Compose does"""
    for file in ('.lando.local.yml', '.lando.yml'):
        path = Path(file)
        if not path.is_file():
            continue
        match = re.search(r'^name:\s*[\'"]?([^\'"\s]+)', path.read_text(), re.MULTILINE)
        if match:
            return match.group(1).replace('-', '').replace('_', '').lower()
    die('No Lando app name found in .lando.local.yml or .lando.yml')
    return ''


def set_path(tree: dict, keys: list[str], value: str) -> None:
    for key in keys[:-1]:
        tree = tree.setdefault(key, {})
    tree[keys[-1]] = value


def routes_from_labels(container: str, labels: dict[str, str]) -> dict:
    """Lando's labels in the file provider's shape: field names are camelCase
    there, lists are lists, and services point at the container by name"""
    raw: dict = {}
    for key, value in labels.items():
        if key.startswith('traefik.http.'):
            set_path(raw, key.split('.')[2:], value)

    http: dict = {'routers': {}, 'middlewares': {}, 'services': {}}
    for name, router in raw.get('routers', {}).items():
        if name.startswith(SLOT_ROUTE_PREFIX):
            continue
        routed = {
            'rule': router['rule'],
            'entryPoints': router['entrypoints'].split(','),
            'service': router['service'],
            'priority': int(router.get('priority', ROUTER_PRIORITY)),
        }
        if router.get('middlewares'):
            routed['middlewares'] = router['middlewares'].split(',')
        if router.get('tls') == 'true':
            routed['tls'] = {}
        http['routers'][name] = routed
    for name, middleware in raw.get('middlewares', {}).items():
        if name.startswith(SLOT_ROUTE_PREFIX):
            continue
        headers = middleware.get('headers', {}).get('customrequestheaders')
        if headers is None or len(middleware) != 1:
            die(f'Unsupported middleware in the labels of {container}: {name}')
        http['middlewares'][name] = {'headers': {'customRequestHeaders': headers}}
    for name, service in raw.get('services', {}).items():
        if name.startswith(SLOT_ROUTE_PREFIX):
            continue
        server = service.get('loadbalancer', {}).get('server', {})
        url = f"{server.get('scheme', 'http')}://{container}:{server.get('port', '80')}"
        http['services'][name] = {'loadBalancer': {'servers': [{'url': url}]}}
    return http


def route_app(project: str) -> None:
    containers = docker(
        'ps', '-a',
        '--filter', f'label=com.docker.compose.project={project}',
        '--filter', 'label=traefik.enable=true',
        '--format', '{{.Names}}',
    ).split()
    if not containers:
        die(f'No container of Lando app {project} is proxied (is the app built?)')
    http: dict = {'routers': {}, 'middlewares': {}, 'services': {}}
    for container in sorted(containers):
        labels = json.loads(docker('inspect', container, '--format', '{{json .Config.Labels}}'))
        for section, entries in routes_from_labels(container, labels).items():
            http[section].update(entries)
    # JSON is YAML, so Traefik reads it as is
    routes = '# Generated by route-in-lando-proxy.py: do not edit\n' + json.dumps({'http': http}, indent=2)
    write_routes(f'lando-{project}', routes)
    print_urls(http['routers'])


def is_wordpress() -> bool:
    path = Path('.lando.yml')
    return path.is_file() and re.search(r'^recipe:\s*wordpress\b', path.read_text(), re.MULTILINE) is not None


def http_status(url: str) -> int | None:
    context = ssl.create_default_context()
    context.check_hostname = False
    context.verify_mode = ssl.CERT_NONE
    try:
        with urllib.request.urlopen(urllib.request.Request(url, method='HEAD'), timeout=5, context=context) as response:
            return response.status
    except urllib.error.HTTPError as error:
        return error.code
    except (urllib.error.URLError, OSError):
        return None


def print_urls(routers: dict) -> None:
    """Lando's own report checks the domains before they are routed, so it
    shows a 404 in place of the URL: print it once the app answers on it"""
    domains = sorted({
        domain
        for router in routers.values() if 'tls' in router
        for domain in re.findall(r'Host(?:Regexp)?\(`([^`]+)`\)', router['rule'])
    })
    path = 'wp-admin/' if is_wordpress() else ''
    for domain in domains:
        url = f'https://{domain}/'
        deadline = time.time() + URL_CHECK_SECONDS
        status = http_status(url)
        while status in (None, 404, 502) and time.time() < deadline:
            time.sleep(1)
            status = http_status(url)
        if status is None or status >= 400:
            log(f'Not answering yet ({status or "no response"}): {url}{path}')
        else:
            log(f'Ready: {url}{path}')


def main() -> None:
    args = sys.argv[1:]
    if args[:1] == ['--write'] and len(args) == 2:
        write_routes(args[1], sys.stdin.read())
    elif args[:1] == ['--remove'] and len(args) == 2:
        remove_routes(args[1])
    elif len(args) <= 1 and not (args and args[0].startswith('-')):
        route_app(args[0] if args else project_from_lando_config())
    else:
        die(__doc__ or '')


if __name__ == '__main__':
    main()
