/**
 * Set webpack public path first so chunk URLs use the correct base (injected by PHP).
 */
import './public-path';

/**
 * Monaco workers required by GraphiQL v5 (Monaco Editor).
 * Must be imported before any graphiql usage in Webpack builds.
 */
import 'graphiql/setup-workers/webpack';
import './monaco-worker-public-path';

import React from 'react';
import ReactDOM from 'react-dom/client';
import 'graphiql/style.css';
import App from './App';

const container = document.getElementById('graphiql');
if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(<App />);
}
