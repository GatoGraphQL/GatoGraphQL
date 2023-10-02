const PluginMetadataConfig = require("./plugin-metadata.config")

module.exports.getGitHubRepoDocsRootURL = function () {
    return `https://raw.githubusercontent.com/${ PluginMetadataConfig.DOCS_GITHUB_REPO_OWNER }/${ PluginMetadataConfig.DOCS_GITHUB_REPO_NAME }`;
}

module.exports.getStablePackageTagForCurrentVersion = function () {
    const NPM_PACKAGE_VERSION = process.env.npm_package_version;
    return NPM_PACKAGE_VERSION.endsWith('-dev') ? PluginMetadataConfig.DOCS_GIT_BASE_BRANCH : NPM_PACKAGE_VERSION;
}