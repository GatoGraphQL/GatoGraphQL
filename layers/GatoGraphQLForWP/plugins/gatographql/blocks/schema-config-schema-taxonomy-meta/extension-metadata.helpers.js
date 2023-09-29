const extensionMetadataConfig = require("./extension-metadata.config")

module.exports.getGitHubRepoDocsRootURL = function () {
    return `https://raw.githubusercontent.com/${ extensionMetadataConfig.GITHUB_REPO_OWNER }/${ extensionMetadataConfig.GITHUB_REPO_NAME }`;
}

module.exports.getStablePackageTagForCurrentVersion = function () {
    const NPM_PACKAGE_VERSION = process.env.npm_package_version;
    return NPM_PACKAGE_VERSION.endsWith('-dev') ? extensionMetadataConfig.GIT_BASE_BRANCH : NPM_PACKAGE_VERSION;
}