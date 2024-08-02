/* eslint-disable no-undef */

import { defineConfig, loadEnv } from 'vite'
import react from '@vitejs/plugin-react'
import { viteStaticCopy } from 'vite-plugin-static-copy'
import path from 'path'
import fs from 'fs'

export default ({ mode }) => {
  let srcConfigPath = 'api/config.php'

  process.env = {...process.env, ...loadEnv(mode, process.cwd(), '')};

  let requiredKeys = ['VITE_APP_NAME', 'VITE_THEME_COLOR']

  for (let key of requiredKeys) {
    if (!process.env[key]) {
      console.error(`\x1b[1;31mRequired Environment Variable Not Found: ${key}.\nPlease set the ${key} environment variable in .env or .env.${mode}.\x1b[0m\n`);
      process.exit(1);
    }
  }

  let basePath;
  let apiUrl;
  let frontendUrl;

  if (mode === 'production') {
    if (fs.existsSync('api/config.prod.php')) {
      console.log("\x1b[36mProduction Specific Configuration File Found: config.prod.php.\nconfig.prod.php will be copied to config.php in the build directory.\x1b[0m\n");
      srcConfigPath = 'api/config.prod.php';
    } else if (fs.existsSync(srcConfigPath)) {
      console.log("\x1b[36mProduction Specific Configuration File Not Found: config.dev.php.\nconfig.php will be copied to config.php in the build directory.\x1b[0m\n");
    } else {
      console.error("\x1b[1;31mConfiguration File Not Found: config.php.\nPlease create a config.php file in the api directory.\x1b[0m\n");
      process.exit(1);
    }

    if (!process.env.BASE_PATH_PROD) {
      console.error("\x1b[1;31mRequired Environment Variable Not Found: BASE_PATH_PROD.\nPlease set the BASE_PATH_PROD environment variable in .env or .env.production.\x1b[0m\n");
      process.exit(1);
    }

    if (!process.env.API_URL_PROD) {
      apiUrl = process.env.BASE_PATH_PROD === '/' ? '/api' : process.env.BASE_PATH_PROD + '/api';
      console.log(`\x1b[33mAPI_URL_PROD environment variable not found. Setting API_URL_PROD to ${apiUrl}/api.\x1b[0m\n`);
    } else {
      apiUrl = process.env.API_URL_PROD;
    }

    if (!process.env.FRONTEND_URL_PROD) {
      console.log(`\x1b[33mFRONTEND_URL_PROD environment variable not found. Setting FRONTEND_URL_PROD to ${process.env.BASE_PATH_PROD}.\x1b[0m\n`);
      frontendUrl = process.env.BASE_PATH_PROD;
    } else {
      frontendUrl = process.env.FRONTEND_URL_PROD;
    }

    basePath = process.env.BASE_PATH_PROD;

  } else {
    if (fs.existsSync('api/config.dev.php')) {
      console.log("\x1b[36mDevelopment Specific Configuration File Found: config.dev.php.\nconfig.dev.php will be copied to config.php in the build directory.\x1b[0m\n");
      srcConfigPath = 'api/config.dev.php';
    } else if (fs.existsSync(srcConfigPath)) {
      console.log("\x1b[36mDevelopment Specific Configuration File Not Found: config.dev.php.\nconfig.php will be copied to config.php in the build directory.\x1b[0m\n");
    } else {
      console.error("\x1b[1;31mConfiguration File Not Found: config.php.\nPlease create a config.php file in the api directory.\x1b[0m\n");
      process.exit(1);
    }

    if (!process.env.BASE_PATH_DEV) {
      console.error("\x1b[1;31mRequired Environment Variable Not Found: BASE_PATH_DEV.\nPlease set the BASE_PATH_DEV environment variable in .env or .env.development.\x1b[0m\n");
      process.exit(1);
    }

    if (!process.env.API_URL_DEV) {
      apiUrl = process.env.BASE_PATH_DEV === '/' ? '/api' : process.env.BASE_PATH_DEV + '/api';
      console.log(`\x1b[33mAPI_URL_DEV environment variable not found. Setting API_URL_DEV to ${apiUrl}.\x1b[0m\n`);
    } else {
      apiUrl = process.env.API_URL_DEV;
    }

    if (!process.env.FRONTEND_URL_DEV) {
      console.log(`\x1b[33mFRONTEND_URL_DEV environment variable not found. Setting FRONTEND_URL_DEV to ${process.env.BASE_PATH_DEV}.\x1b[0m\n`);
      frontendUrl = process.env.BASE_PATH_DEV;
    } else {
      frontendUrl = process.env.FRONTEND_URL_DEV;
    }

    basePath = process.env.BASE_PATH_DEV;
  }

  console.log(`\x1b[32mAPI URL: ${apiUrl}\nFrontend URL: ${frontendUrl}\nBase Path: ${basePath}\x1b[0m\n`);

  if (basePath == '/') {
    basePath = '';
  } else {
    basePath = basePath.replace(/\/?$/, '/');
  }

  process.env.VITE_API_URL = apiUrl.replace(/\/?$/, ''); 
  process.env.VITE_FRONTEND_URL = frontendUrl.replace(/\/?$/, '');

  // Write the build environment to a PHP file
  const buildEnvContent = `<?php\n$buildEnv = '${mode}';\n?>`;
  fs.writeFileSync(path.resolve(__dirname, 'api/buildenv.php'), buildEnvContent);

  return defineConfig({
    plugins: [
      react(),

      // Copy the api folder to the build directory
      viteStaticCopy({
        targets: [
          {
            src: ['api/**/*', '!api/config.*php*'],
            dest: '.'
          },
          {
            src: srcConfigPath,
            dest: '.',
            rename: () => 'config.php'
          },
          {
            src: 'api/buildenv.php',
            dest: '.'
          }
        ],
        structured: true
      })[1] // 1 For build only mode (don't run on vite serve)
    ],
    base: basePath,
    build: {
      rollupOptions: {
        output: {
          manualChunks: {
            react:  ['react', 'react-dom', 'react-router-dom', '@tanstack/react-query'],
            style:  ['react-toastify', 'react-bootstrap', '@fortawesome/react-fontawesome', '@fortawesome/fontawesome-svg-core', '@fortawesome/free-solid-svg-icons', 'react-toastify/dist/ReactToastify.css'],
            vendor: ['sortablejs', 'react-sortablejs', '@radix-ui/react-context-menu'],
            sentry: ['@sentry/react'],
          }
        }
      },
      emptyOutDir: false,
    },
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
        '$assets': path.resolve(__dirname, 'src/assets'),
      }
    },
  })
}
