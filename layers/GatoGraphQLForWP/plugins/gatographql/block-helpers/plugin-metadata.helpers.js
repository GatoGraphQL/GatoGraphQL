const PluginMetadataConfig = require("../block-config/plugin-metadata.config")

const getGitHubRepoDocsRootURL = function () {
    return `https://raw.githubusercontent.com/${ PluginMetadataConfig.DOCS_GITHUB_REPO_OWNER }/${ PluginMetadataConfig.DOCS_GITHUB_REPO_NAME }`;
}

const getGitHubRepoDocsBranchOrTag = function () {
    const NPM_PACKAGE_VERSION = process.env.npm_package_version;
    return NPM_PACKAGE_VERSION.endsWith('-dev') ? PluginMetadataConfig.DOCS_GIT_BASE_BRANCH : NPM_PACKAGE_VERSION;
}

module.exports.getGitHubRepoDocsRootPathURL = function () {
    return `${ getGitHubRepoDocsRootURL() }/${ getGitHubRepoDocsBranchOrTag() }/layers/GatoGraphQLForWP/plugins/gatographql`
}