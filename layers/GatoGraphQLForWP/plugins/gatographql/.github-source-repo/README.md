# Gato GraphQL - DEV Source

Here you will find the DEV version of the [Gato GraphQL](https://gatographql.com) plugin.

Use this repo to:

- Test the upcoming features, and provide feedback
- Use in your site (at your own risk!), if you depend on a new feature and can't wait for it
- Explore the source code, to replicate for your own extensions
- Contribute (if you are up for it ;))

Differences with PROD:

- Source code in PHP 8.1
- Namespaces are not scoped

(PROD is downgraded to PHP 7.4, and scoped)

## Installing

Clone the repo into your `wp-content/plugins` folder:

```bash
cd {wp_site_root}/wp-content/plugins
git clone https://github.com/GatoGraphQLForWordPress/gatographql-source gatographql
```

then keep updated:

```bash
cd gatographql
git fetch && git rebase
```

Alternatively, click on **Code >> Download ZIP**, and install the WordPress plugin as usual. (It won't replace the original plugin, as this .zip file is installed under plugin slug `gatographql-source-master`.)

## Change log

Please see [CHANGELOG](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/layers/GatoGraphQLForWP/plugins/gatographql/CHANGELOG.md) for more information on what has changed recently.
