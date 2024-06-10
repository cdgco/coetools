# COE Tools

![Light Mode Home](https://github.com/cdgco/coetools/blob/master/docs/light-home.png?raw=true)

> [!IMPORTANT]
>## COE Tools React is coming soon!
> COE Tools has been rewritten from the ground up in React, featuring a modular authentication system (LDAP, SAML, & OAuth), a blazing fast search engine, separate production and staging environments, plugins, an updated UI, and improved error handling. COE Tools React still runs on a PHP backend but no longer requires Apache and LDAP. COE Tools React is undergoing some finishing touches / documentation and will be open sourced soon.

COE Tools is the internal tool management system for OSU's College of Engineering IT department. It is a dynamic link / tool manager with two primary sections, the toolbar and the tool directory.

All tools are managed from the tool directory, which not only allows you to add remove or edit tools from the website, but search, filter and view extra data about all tools.
The included directory editor contains all necessary fields to change both what information is linked to a tool and how the tool should be displayed (or not) in the toolbar and tool directory.

The tool is a dynamic LDAP authenticated system with user / admin roles, allowing administrators to add / remove / edit tools while users can only view tools.
Furthermore, tools can be hidden and shown only to users in the "admin" group.

Tools may be added from the web tool directory as either a link that opens in a new tab, the same tab, or an iframe within the COE Tools interface.
Tools may be organized with a name, description, and category, and can be added directly to the toolbar, a toolbar dropdown category, or only shown in the tool directory.

The tool directory shows a table of tools which may be sorted by name, category or admin only status, filtered by category, and searched by any metadata linked to the tool.

COE Tools features user specific memory which allows users to add specific tools to their favorites which will save them to their home page.
Additionally, COE Tools will keep track of a users 5 most recent tools on their home page.
Finally, COE Tools allows users to customize their interface with a drag and drop toolbar editor to rearrange items and hide irrelevant tools, as well as a light / dark mode switch and a button to reset the interface to it's defaults.

# Requirements

* PHP / Apache server with php-ldap extension installed
* LDAP server
* MySQL Server
* yarn (to install dependencies)

# Installation

* Run `yarn install` in the `frontend` folder
* Create a MySQL database and enter the credentials in `frontend/includes/db.php`
* Initialize your MySQL db with the `init.sql` file
* Replace `ou=groups,dc=example,dc=com` and `CN=example,OU=Groups,DC=example,DC=com` in `.htaccess` with your distinguished name.
* Replace `dc=example,dc=com` and `ou=groups,dc=example,dc=com` in `frontend\includes\ldap.php` with your distinguished name.
* Replace `dc=example,dc=com` and `ou=groups,dc=example,dc=com` in `frontend\includes\menu.php` with your distinguished name.
* Replace `support` on line 19 in `frontend\includes\menu.php` with the name of the ldap group who should have admin privelages.
* Replace `coe_` on line 19 in `frontend\includes\menu.php` with the prefix of ldap users who should have admin privelages.
* Replace `roeserc` on line 19 in `frontend\includes\menu.php` with the usernane of an ldap user who should have admin privelages.
* Repeat the 3 previous steps (or remove the queries) to add or restrict admin access to users or groups.

# Adding / Editing Tools

1. To add or edit a tool, go to the "Tool Directory" and scroll down to the bottom.
2. Click "Edit Rows"
3. Either click "New row" or the edit icon next to a preexisting tool
4. Fill out all fields.
5. The Category field will give you a dropdown of all preexisting categories, but you can enter a new category and it will add that category to the toolbar and filtering list, if applicable.
6. Save your changes. Edits made from the editor will be apparent immediately, but some features need a refresh before they are fuully functional.
7. Click "Cancel" at the bottom of the page to exit editing mode.

# Code & Dependencies

The reftool is built entirely around bootstrap and footable, but also requires font-awesome, popper.js (included with bootstrap), moment.js (for font-awesome), and jquery.

All dependencies except for footable, moment.js, and font-awesome (which may be added later) are handled by yarn pacakge manager for simplicity, although most packages are locked at old versions for compatibility.

Although footable is no longer maintained, it is one of the best an all in one JS table systems, with the included search, filtering and editing. I've made some small CSS changes to ensure visual compatibility with bootstrap 4, but everything else works out of the box.

All dependencies and assets are stored in the frontend folder, while the only public facing pages are index.php and directory.php.

All tools are stored in a MySQL database with 1 row for each tool. Tools are grabbed through PHP with a database connection in the frontend/includes/db.php file. Scripts to add (create.php), edit (change.php) or delete (delete.php) tools in MySQL are also stored in frontend/includes.

Custom CSS is in frontend/assets/css/style.css. Most CSS is just for added compatibility with footable.

frontend/menu.php contains the function to generate a dynamic menu based off data from MySQL. Creates associative array from MySQL rows, filters by display type then loops through adding tools as list items in html. "data-sort" attribute is used by JS script in frontend pages to sort all list items alphabetically.

# Example Images

## Dark mode with drag and drop editor open
![Dark Mode Home With Editor](https://github.com/cdgco/coetools/blob/master/docs/dark-home-editor.png?raw=true)

## Light mode tool directory with category filter / search open
![Light Mode Tool Directory](https://github.com/cdgco/coetools/blob/master/docs/light-directory-dropdown.png?raw=true)

## Light mode tool directory add-tool dialog
![Light Mode Add Tool Dialog](https://github.com/cdgco/coetools/blob/master/docs/light-add-tool-dialog.png?raw=true)
