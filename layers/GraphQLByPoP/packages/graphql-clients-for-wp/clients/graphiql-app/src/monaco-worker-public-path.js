/**
 * Wrap Monaco worker creation so workers run with the correct __webpack_public_path__
 * (build base URL). Worker bundles have s.p="./" hardcoded and use importScripts(s.p + chunk).
 * We fetch the worker script and replace the public path so chunk URLs resolve correctly.
 *
 * Must run after graphiql/setup-workers/webpack sets MonacoEnvironment.getWorker.
 * Uses settings.workerChunks (from PHP manifest) and settings.buildBaseURL.
 */
if (typeof globalThis.MonacoEnvironment !== 'undefined' && globalThis.MonacoEnvironment.getWorker) {
  const settings = window.graphQLByPoPGraphiQLSettings || {};
  const base = settings.buildBaseURL || window.graphqlclientsforwpGraphiQLBuildURL || '';
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
        .then((res) => {
          if (!res.ok) throw new Error('Worker fetch ' + res.status);
          return res.text();
        })
        .then((code) => {
          // Worker bundles have s.p="./" or r.p="./" hardcoded (webpack uses different names).
          // Replace with full build URL so importScripts(x.p + chunk) resolves correctly.
          const escapedBase = base.replace(/\\/g, '\\\\').replace(/"/g, '\\"');
          const replacement = (letter) => letter + '.p="' + escapedBase + '"';
          let patched = code
            .replace('s.p="./"', replacement('s'))
            .replace('r.p="./"', replacement('r'));
          if (patched === code) {
            patched = code.replace(
              /([a-z])\.p\s*=\s*["']([^"']*)["']/gi,
              (match, letter, val) => (val.startsWith('http') ? match : replacement(letter))
            );
          }
          const blob = new Blob([patched], { type: 'application/javascript' });
          return new Worker(URL.createObjectURL(blob));
        });
    };
  }
}
