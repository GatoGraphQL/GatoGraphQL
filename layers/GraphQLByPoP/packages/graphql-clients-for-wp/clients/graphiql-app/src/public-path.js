/**
 * Set webpack public path at runtime so chunk URLs resolve to the plugin build folder.
 * Must be imported first (before any other imports) in the entry.
 */
if (typeof window !== 'undefined' && window.graphqlclientsforwpGraphiQLBuildURL) {
  // eslint-disable-next-line no-undef
  __webpack_public_path__ = window.graphqlclientsforwpGraphiQLBuildURL;
}
