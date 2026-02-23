/**
 * Wrap Monaco worker creation so workers run with the correct __webpack_public_path__
 * (build base URL). Otherwise workers use their script directory and request chunks
 * at .../build/static/js/static/js/xxx.chunk.js (double path).
 *
 * Must run after graphiql/setup-workers/webpack sets MonacoEnvironment.getWorker.
 * Uses settings.workerChunks (from PHP manifest) and settings.buildBaseURL.
 */
if (typeof globalThis.MonacoEnvironment !== 'undefined' && globalThis.MonacoEnvironment.getWorker) {
  const settings = window.graphQLByPoPGraphiQLSettings || {};
  const base = settings.buildBaseURL || window.gatographqlGraphiQLBuildURL || '';
  const workerChunks = settings.workerChunks || {};
  if (base && Object.keys(workerChunks).length > 0) {
    const labelToChunkId = { json: '5997', graphql: '8378' };
    const originalGetWorker = globalThis.MonacoEnvironment.getWorker;
    globalThis.MonacoEnvironment.getWorker = function (editor, label) {
      const chunkId = labelToChunkId[label] || '5914';
      const path = workerChunks[chunkId];
      if (!path) {
        return originalGetWorker.call(this, editor, label);
      }
      const workerUrl = base + path.replace(/^\.\//, '');
      return fetch(workerUrl)
        .then((res) => res.text())
        .then((code) => {
          const bootstrap = "var __webpack_public_path__='" + base.replace(/'/g, "\\'") + "';";
          const blob = new Blob([bootstrap + code], { type: 'application/javascript' });
          return new Worker(URL.createObjectURL(blob));
        });
    };
  }
}
