<?php

/*
 * Rename this file to config.php to be used on
 * both development and production builds, or 
 * create copies named config.dev.php and config.prod.php
 * for separate development and production configurations.
 * 
 * config.prod.php and config.dev.php take precedence over config.php.
 * Upon build, config.prod.php and config.dev.php will be renamed to config.php
 * in their respective build directories.
 */

// URLs to the frontend (without trailing slashes or /#/).
$basePathProd = 'https://tools.example.com/dist';
$basePathDev = 'https://tools.example.com/staging';

// If your API is hosted on a different domain, uncomment and modify these URLs.
// $apiUrlProd  = 'https://tools.example.com/dist/api';
// $apiUrlDev  = 'https://tools.example.com/staging/api';

////////////////////////////////
//    Database Credentials    //
////////////////////////////////

$dbSettings = array(
  'host'     => '',
  'db'       => '',
  'user'     => '',
  'password' => '',
  'type'     => 'mysql', // "mysql" or "postgresql".
);

///////////////////////////////
//   Authentication Config   //
///////////////////////////////

$authMethod = "ldap"; // "ldap", "saml", or "oauth".
$stripDomain = false; // Strip domain from username / email (e.g. "user@domain.com" -> "user").

/* 
 * To specify if a user is admin (can manage tools and see "staff only" tools) 
 * specify a username prefix or list of usernames.
 *
 * If either field is left empty, the corresponding check will not be performed.
 */

$adminPrefix = "admin_"; // Prefix for admin usernames.
$adminUsers = []; // Array of admin usernames.

// LDAP Configuration

$ldapSettings = array(
  'host'        => '',
  'port'        => 389,
  'baseDN'      => '',
  'userOU'      => '',
  'userGroups'  => [], // Restrict access to users in these groups.
  'adminGroups' => [], // Users in these groups are considered staff.
);

// SAML Configuration

/*
 * SAML is powered by the OneLogin PHP SAML Toolkit.
 * See https://github.com/SAML-Toolkits/php-saml/blob/4.x-dev/settings_example.php
 * for more information on settings and additional configuration options.
 */

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

// OAuth Configuration

/*
 * OAuth is powered by The PHP League's OAuth2 Client
 * See https://github.com/thephpleague/oauth2-client
 * for more information on settings and additional configuration options.
 */

$oauthSettings = array(
  'clientId'                => '',
  'clientSecret'            => '',
  'urlAuthorize'            => '',
  'urlAccessToken'          => '',
  'urlResourceOwnerDetails' => '',
  'userGroups'              => [], // Restrict access to users in these groups.
  'adminGroups'             => [], // Users in these groups are considered staff.
  'scope'                   => 'openid profile email offline',
  'prompt'                  => 'select_account',
)

?>
