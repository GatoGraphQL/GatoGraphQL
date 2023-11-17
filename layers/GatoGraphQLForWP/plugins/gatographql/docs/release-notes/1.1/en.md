# Release Notes: 1.1

Here's a description of all the changes.

## Tested with WordPress 6.4

The plugin has been tested with WordPress 6.4, and the corresponding entry in the plugin's header (`"Tested up to"`) has been updated.

## Install setup data: Persisted Queries for common admin tasks

@todo Complete "Install Commonly Used Persisted Queries"

## Purge container when autoupdating a depended-upon plugin

When executing a plugin update from the WP dashboard, if the plugin is a dependency by a Gato GraphQL extension, then the service container will be purged. This avoid a potential exception thrown when the plugin's new version has incompatible code with the previous version.
