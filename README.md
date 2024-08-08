# COE Tools

![Home](https://github.com/cdgco/coetools/blob/main/docs/home.png?raw=true)  | ![Tool Directory](https://github.com/cdgco/coetools/blob/main/docs/directory.png?raw=true)
:-------------------------:|:-------------------------:|


COE Tools is a web tool management system built for the Oregon State University COE IT department which provides a database for tracking links and web tools with a dynamic toolbar, tool inventory, recent and favorite tools, profile management, and plugins. It supports user authentication, user roles, and user preferences with the option for either a LDAP, SAML, or OAuth authentication provider.

COE Tools is built in PHP and JavaScript with a React frontend, and includes a development and production environment with separate build configurations for local and remote development.

## Requirements

- PHP 7+
- MySQL, MariaDB, or PostgreSQL database
- LDAP, SAML, or OAuth authentication provider
- Node.js 16+ (For building only)
- NPM (For building only)
- Composer (For building only)

## Installation

Install the dependencies.

```bash
npm install
```

> This will attempt to automatically run `composer install` inside of the `api` folder. If this fails, you can run `composer install` manually.

Configure the frontend by copying `.env.example` to `.env` and updating the configuration options.

```bash
cp .env.example .env
```

Set the `BASE_PATH_DEV` and `BASE_PATH_PROD` variables to the full URL (including protocol) of the dist and staging directories on the web server, without the trailing slash or `/#/` path. If you are not using the staging environment, you can set `BASE_PATH_DEV` to the same value as `BASE_PATH_PROD`.

```
BASE_PATH_PROD=https://tools.example.com/dist
BASE_PATH_DEV=https://tools.example.com/staging
```

If desired, set the app name and theme color.

```
VITE_APP_NAME = "Web Tools"
VITE_THEME_COLOR = "#60a5fa"
```

If you are hosting the API on a different server, or are aliasing the frontend to a different directory, you can set the `API_URL_PROD` / `API_URL_DEV` and `FRONTEND_URL_PROD` / `FRONTEND_URL_DEV` variables to the full URL of the API server and source directory, respectively.

Then, configure the backend connection by copying `config.example.php` to `config.php` in the `api` folder and updating the configuration options.

```bash
cp api/config.example.php api/config.php
```

First, set `$basePathProd` and `$basePathDev` to the full URL of the frontend for the production and development environments, without the trailing slash or `/#/` path. If you are not using the staging environment, you can set `$basePathDev` to the same value as `$basePathProd`.

```php
$basePathProd = 'https://tools.example.com/dist';
$basePathDev = 'https://tools.example.com/staging';
```

Next, enter the details for the database connection.

```php
$dbSettings = array(
  'host' => 'localhost',
  'db' => 'coetools',
  'user' => 'username',
  'password' => 'password',
  'type' => 'mysql' // "mysql" or "postgresql"
);
```

> COE Tools has been tested with MySQL 5.7, MySQL 8.3, MariaDB 10.6 and PostgreSQL 15.1.

Finally, choose your authentaction provider and configure the settings.

```php
$authMethod = 'ldap'; // ldap, saml, or oauth
$adminPrefix = "admin_"; // Username prefix for admin users
$adminUsers = ['admin_username']; // Array of admin usernames
```

Using the `$adminPrefix` and `$adminUsers` variables, you can specify a prefix for admin usernames and an array of admin usernames. Admin users will have permission to view restricted tools and have full edit permissions to the tool database. Leaving these variables empty will skip the admin check.

By default, COE Tools will use user's full username or email address as their COE Tools username. If you wish to strip the domain from and email address, you can set `$stripDomain` to `true`.

```php
$stripDomain = false; // Strip domain from email address
```

### LDAP

To use LDAP authentication, set `$authProvider` to `'ldap'` and configure the LDAP settings.

```php
$ldapSettings = array(
    'host' => 'ldaps://ldap.example.com',
    'port' => 389,
    'baseDN' => 'dc=example,dc=com',
    'userOU' => 'ou=users',
    'userGroups' => [],
    'adminGroups' => []
);
```

You can optionally specify groups to restrict login to, and to provide admin access to. If the user is not in the specified groups, they will not be able to log in. If the user is in the admin groups, they will have full edit permissions to the tool database.

Leaving the `userGroups` and `adminGroups` arrays empty will allow all users to log in and will give all users full edit permissions to the tool database.

### SAML

To use SAML authentication, set `$authProvider` to `'saml'` and configure the SAML settings.

```php
$samlSettings = array(
  'sp' => array(
      /* x509 certificate to validate incoming assertions */
      'x509cert' => '',
      'privateKey' => '',

      /*
       * If you plan to update the x509cert and privateKey
       * you can define the new x509cert here and it will be 
       * published so the IdP can prepare for cert rollover.
       */
      // 'x509certNew' => '',
  ),
  'idp' => array(
      'entityId' => '',
      'singleSignOnService' => array(
          'url' => '',
      ),
      'singleLogoutService' => array(
          'url' => '',
      ),
      /* Public x509 certificate of the IdP */
      'x509cert' => '',

      /* To add more than one IdP certificate, or separate certs for
       * signing/encryption, uncomment the following lines and add
       * additional certificates as needed.
       */
      // 'x509certMulti' => array(
      //      'signing' => array(
      //          0 => '<cert1-string>',
      //      ),
      //      'encryption' => array(
      //          0 => '<cert2-string>',
      //      )
      // ),
  ),
);
```

Generate the x509 certificate and private key for the SAML service provider and enter the base64 encoded strings in the `x509cert` and `privateKey` fields. For help generating the certificate and key, see the [OneLogin SAML Tool](https://www.samltool.com/self_signed_certs.php).

Enter your SAML IdP settings in the `idp` array. The `entityId` should be the entity ID of the SAML IdP, the `singleSignOnService` should be the URL of the IdP's SSO service, and the `x509cert` should be the public x509 certificate of the IdP. The `singleLogoutService` is optional and should be the URL of the IdP's SLO service.

The SAML service provider information will be published to `/api/auth/saml/metadata.php` and should be entered in the IdP's configuration.
* SP Entity ID: `https://tools.example.com/api/auth/saml/metadata.php`
* ACS (Login Callback) URL: `https://tools.example.com/api/auth/saml/auth.php`
* SLS (Logout) URL: `https://tools.example.com/api/auth/saml/logout.php`

SAML login requests are sent with the `ForceAuthn` flag set to `true` to force the IdP to reauthenticate the user. This is used to prevent authetication loops when logging out but can be disabled by setting the argument to `false` in `api/auth/saml/login.php`.

```php
$auth->login($redirect, array(), false); 
```

SAML authentication is built with the [OneLogin SAML PHP Toolkit](https://github.com/SAML-Toolkits/php-saml). Visit the SAML Toolkit repository for more information on configuring SAML authentication.

SAML has been tested with the following providers, but settings may need to be adjusted depending on the provider.
- Microsoft Azure AD
- Auth0
  - Set IdP Entity ID to `urn:org.auth0.com` where `org` is your Auth0 tenant.
- Frontegg
  - Set IdP Entity ID to `http://frontegg.com/saml`.
  - Add the following attribute mappings:
    - `http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name` -> `name`
    - `http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress` -> `email`
### OAuth

To use OAuth authentication, set `$authProvider` to `'oauth'` and configure the OAuth settings.

```php
$oauthSettings = array(
  'clientId'                => 'client_id',
  'clientSecret'            => 'client_secret',
  'urlAuthorize'            => 'https://example.com/oauth/authorize',
  'urlAccessToken'          => 'https://example.com/oauth/token',
  'urlResourceOwnerDetails' => 'https://example.com/oauth/resource',
  'userGroups'              => [],
  'adminGroups'             => [],
  'scope'                   => 'openid profile email offline',
  'propmt'                  => 'select_account'
);
```

Enter the OAuth client ID and secret, the authorization URL, the access token URL, and the resource owner details URL.

OAuth authentication relies on an ID token to retrieve user information. The ID token should contain the following fields:
- `name`: The user's full name.
- `email`: The user's email address.

You can optionally specify groups to restrict login to, and to provide admin access to. If the user is not in the specified groups, they will not be able to log in. If the user is in the admin groups, they will have full edit permissions to the tool database.

Leaving the `userGroups` and `adminGroups` arrays empty will allow all users to log in and will give all users full edit permissions to the tool database.

OAuth login requests are sent with the `prompt` parameter set to `select_account` to force the IdP to reauthenticate the user. This is used to prevent authetication loops when logging out but can be disabled by removing the `prompt` parameter in  the `$oauthSettings` variable.

OAuth authentication is built with the [The PHP League's OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client). Visit the OAuth 2.0 Client repository for more information on configuring OAuth authentication.

OAuth has been tested with the following providers, but scopes may need to be adjusted depending on the provider. The following providers have been tested with the provided scopes:
- Microsoft Azure AD: `openid profile email User.Read`
- Auth0: `openid profile email offline_access`
- Kinde: `openid profile email offline`
- Frontegg: `openid profile email offline`

### Configuration Environments

To specify a different configuration to be used in the development and production environments, you can create a `config.dev.php` and `config.prod.php` file in the `api` folder. `config.prod.php` will take precedence over `config.php` in the production environment and `config.dev.php` will take precedence over `config.php` in the development environment.

Similarly, you can create a `.env.development` and `.env.production` file in the root directory to specify different configurations for the development and production environments. `.env.production` will take precedence over `.env` in the production environment and `.env.development` will take precedence over `.env` in the development environment.

Once configured, build the project by running the following command:

```bash
npm run build
```

This will build the project in the `dist` folder. The app can be accessed from the `dist` folder which you can configure to be served from the root directory of your web server via nginx or Apache.

## Building

COE Tools is bundled via Vite which checks the configuration, copies the API files, and compiles the frontend.

Once the project is configured, you can build the server by running the following commands:

```
npm run build
```

or

```
npm run build:staging
```

This will build the project in the `dist` or `staging` folder. The app can be accessed from the `dist` or `staging` folder which you can configure to be served from the root directory of your web server via nginx or Apache.

Note that you may have to run the `chmod 755` command manually if developing from a Windows machine.

When testing the application, you can also append the build command with `:watch` to watch for changes and rebuild the project automatically.

```bash
npm run build:watch
```

## Extensions

COE Tools supports extensions which can be added to the `extensions` folder in the `src` directory. Extensions can add custom cards, pages, and settings to the application.

The extensions directory has a folder for each type of extension: `cards`, `pages`, and `settings`, as well as a `manifest.json` file for each type of extension.

All extensions have access to APIs allowing them to save global and per-user data to the database.

### Cards

Cards are displayed on the dashboard and can be enabled or disabled by the user, or force enabled for all users.

Extension cards can be either a single file, or a a folder with a JSX component as the entry point.

The JSX file must use the `StatusCardStatic` or `StatusCardAccordion` component which can be imported from `@/components/Cards`.

```jsx
import { StatusCardStatic } from '@/components/Cards';
```

The `StatusCardStatic` component is a single card that takes children as a prop.

```jsx
<StatusCardStatic title="Card Title">
  <p>Card Content</p>
</StatusCardStatic>
```

The `StatusCardAccordion` component is an accordion card that takes children and a header as props. The accordion will be collapsed by default.

```jsx
<StatusCardAccordion header="Card Title">
  <p>Card Content</p>
</StatusCardAccordion>
```
The JSX component can accept the following props to interact with the COE Tools API:
- `getGlobalData`: A function that returns the global data for the extension.
- `setGlobalData`: A function that sets the global data for the extension.
- `getUserData`: A function that returns the user data for the extension.
- `setUserData`: A function that sets the user data for the extension.
- `manifest`: An object containing the extension's manifest data.

Many API functions require passing the DataContext via `useContext` from `@/context/DataContext`.

```jsx
import { useContext } from 'react';
import { DataContext } from '@/context/DataContext';

const context = useContext(DataContext);

...

await getGlobalData(manifest.id, context);
await getUserData(manifest.id, context);
await setGlobalData(manifest.id, data);
await setUserData(manifest.id, data, context);
```

Multiple example cards are included in the `src/extensions/cards` directory.

The manifest for an extension card must include the following fields:
- `id`: A unique identifier for the extension.
- `title`: The name of the extension.
- `forceEnable`: A boolean to force enable the extension for all users.
- `staffOnly`: A boolean to restrict the extension to staff users.
- `component`: An `import()` statement to the JSX component.

```
{
  title: 'Example Card',
  id: 'example',
  forceEnable: true,
  staffOnly: false,
  component: import('@/extensions/cards/ExampleCard.jsx'),
}
```

### Pages

Pages are tools that are built into the application. Any pages that are added can automatically be added to the toolbar, search engine, and tool directory, depending on the display preferences.

Pages can be either a single file, or a a folder with a JSX component as the entry point.

The JSX file must use the `DefaultLayout` component which can be imported from `@/layouts`.

```jsx
import { DefaultLayout } from '@/layouts';
```

The `DefaultLayout` component is a layout that takes children as a prop.

```jsx
<DefaultLayout>
  <p>Page Content</p>
</DefaultLayout>
```

The JSX component can accept the following props to interact with the COE Tools API:
- `getGlobalData`: A function that returns the global data for the extension.
- `setGlobalData`: A function that sets the global data for the extension.
- `getUserData`: A function that returns the user data for the extension.
- `setUserData`: A function that sets the user data for the extension.
- `manifest`: An object containing the extension's manifest data.

Many API functions require passing the DataContext via `useContext` from `@/context/DataContext`.

```jsx
import { useContext } from 'react';
import { DataContext } from '@/context/DataContext';

const context = useContext(DataContext);

...

await getGlobalData(manifest.id, context);
await getUserData(manifest.id, context);
await setGlobalData(manifest.id, data);
await setUserData(manifest.id, data, context);
```

Multiple example pages are included in the `src/extensions/pages` directory.

The manifest for an extension page must include the following fields:
- `id`: A unique identifier for the extension.
- `title`: The name of the extension.
- `description`: A description of the extension.
- `category`: The category of the extension.
- `link`: The link to the extension.
- `protected`: A boolean to restrict the extension to logged in users.
- `staffOnly`: A boolean to restrict the extension to staff users.
- `display`: A string to determine where the extension is displayed. Options are `0` for show in category dropdown, `1` for show directly in toolbar, and `2` for hide from toolbar.

```
{
  id: 'example',
  title: 'Example Page',
  description: 'Example page description',
  category: 'Example Category',
  link: 'example',
  protected: true,
  staffOnly: false,
  display: '1',
  component: import('@/extensions/pages/ExamplePage.jsx'),
},
```

### Settings

Settings are displayed in the user settings page and can be used to configure the extension.

Settings can be either a single file, or a a folder with a JSX component as the entry point.

Settings must use an id that matches an enabled extension card or page.

The JSX component can accept the following props to interact with the COE Tools API:
- `getUserData`: A function that returns the user data for the extension.
- `setUserData`: A function that sets the user data for the extension.
- `manifest`: An object containing the extension's manifest data.

Note that on settings pages, these functions do not interact with the database directly, but instead update the user's settings in the frontend and wait for the user to save the settings.

Because of this, you should call `setUserData` every time a setting is changed, and `getUserData` when the component mounts to update the settings.

```jsx
await getUserData(manifest.id);
await setUserData(manifest.id, data);
```

Multiple example settings are included in the `src/extensions/settings` directory.

The manifest for an extension settings page must include the following fields:
- `id`: A unique identifier for the extension.
- `title`: The name of the extension.
- `staffOnly`: A boolean to restrict the extension to staff users.
- `component`: An `import()` statement to the JSX component.

```
{
  'title': 'Example Settings',
  'id': 'example',
  'staffOnly': false,
  'component': import('@/extensions/settings/ExampleSettings.jsx')
},
```
