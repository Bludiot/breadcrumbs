# Breadcrumbs

Breadcrumbs navigation with options for Bludit CMS.

![Tested up to Bludit version 3.16.2](https://img.shields.io/badge/Bludit-3.16.2-e6522c.svg?style=flat-square "Tested up to Bludit version 3.16.2")
![Minimum PHP version is 7.4](https://img.shields.io/badge/PHP_Min-7.4-8892bf.svg?style=flat-square "Minimum PHP version is 7.4")
![Tested on PHP version 8.2.4](https://img.shields.io/badge/PHP_Test-8.2.4-8892bf.svg?style=flat-square "Tested on PHP version 8.2.4")

The Breadcrumbs plugin adds SEO friendly breadcrumb style navigation links to singular and loop pages, search pages, error pages, and if the User Profiles plugin is active, profile pages.

The plugin also includes an optional sidebar widget to display the breadcrumbs, provided the active theme has the default Bludit sidebar.
Template Locations

The breadcrumbs navigation may be added automatically via theme template hook. Options include one custom hook provided by the Breadcrumbs plugin is
`Theme::plugins( 'breadcrumbs' );` and must be added to the active theme where you want the breadcrumbs to appear. Two other options are hooks provided by Bludit, `Theme::plugins( 'pageBegin' );` and `Theme::plugins( 'pageEnd' );`, however there is no guarantee that the active theme will call these hooks.

The active theme has the breadcrumbs hook built into it.
Template Tags

As long as the Breadcrumbs plugin is active, various template tag functions are available to use in custom page templates. In case the plugin is deactivated it is best to wrap the template tags in `if ( getPlugin( 'Breadcrumbs' ) { ... }` to prevent your website breaking.

Following is a list of template tags available. All tags must be echoed to print the result. Also, all tags accept a string parameter for custom separators (e.g. trail( '»' ); ).

``` php
Breadcrumbs\trail(); for Automatic Breadcrumbs
Breadcrumbs\loop_crumbs(); for Blog Pages
Breadcrumbs\cat_crumbs(); for Category Pages
Breadcrumbs\tag_crumbs(); for Tag Pages
Breadcrumbs\post_crumbs(); for Post Pages
Breadcrumbs\page_crumbs(); for Static Pages
Breadcrumbs\user_crumbs(); for Profile Pages
Breadcrumbs\search_crumbs(); for Search Results
Breadcrumbs\error_crumbs(); for 404 Error Page
```
