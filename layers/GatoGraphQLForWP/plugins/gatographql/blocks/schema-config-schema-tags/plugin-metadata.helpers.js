const PluginMetadataConfig = require("./plugin-metadata.config")

module.exports.getGitHubRepoDocsRootURL = function () {
    return `https://raw.githubusercontent.com/${ PluginMetadataConfig.DOCSGITHUB_REPO_OWNER }/${ PluginMetadataConfig.DOCSGITHUB_REPO_NAME }`;
}

module.exports.getStablePackageTagForCurrentVersion = function () {
    const NPM_PACKAGE_VERSION = process.env.npm_package_version;
    return NPM_PACKAGE_VERSION.endsWith('-dev') ? PluginMetadataConfig.DOCSGIT_BASE_BRANCH : NPM_PACKAGE_VERSION;
}