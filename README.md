## Troy Web WordPress Technical Assessment

This repository contains the WordPress site for the Troy Web WordPress Technical Assessment. 
The site is built using the **Monotone Flex Page Theme** as a starting point (renamed `troyweb_applicant`). 

The theme includes a custom Flex Page Template that allows theme developers to create custom page layouts using 
Advanced Custom Fields (ACF) Flexible Content fields. This feature requires ACF Pro, and you must 
assign the "Flex Page Template" to a page in the WordPress admin. There is an example page at 
`https://this-site.com/flex-page-example` that demonstrates the Flex Page Template in action. This feature isn't required for the assessment but is included for reference.

See the theme's README.md file for more information on this theme's features.

### Setup Instructions:

1. **Clone the Git Repository:**
   ```bash
   git clone https://github.com/lancemonotone/troyweb_wp.git
   ```
   
2. **Navigate to the Project Directory:**
   ```bash
   cd troyweb_wp
   ```
   
3. **Install Dependencies using Composer:**
   ```bash
   composer install
   ```

## Why use a `.env` File?
The `.env` file is used to keep sensitive configuration information separate from the codebase and repository. It also makes migrating the site to a new environment easy.

### Create and Populate `.env` File:
1. Create a new `.env` file in the root directory of the project.
2. Set the environment variables to the appropriate values for your site. 

   ```plaintext
    WP_ENV=[local, development, production]
    WP_HOME=[https://somesite.com]
    WP_SITEURL=[https://somesite.com]
    TABLE_PREFIX=[wp_]
    DB_NAME=[my_database]
    DB_USER=[my_username]
    DB_PASSWORD=[my_password]
    DB_HOST=[my_host]
   ```

3. Setting `WP_ENV` to 'local' or 'development' will enhance debugging capabilities on the site. See the `IS_DEBUG_MODE` constant in `wp-config.php` to configure these capabilities.
4. Easily update the site URLs by changing the `WP_HOME` and `WP_SITEURL` values.
5. Save the `.env` file with your specific configuration details. The `vlucas/wpdotenv` Composer package is used to load the `.env` file into the `$_ENV` superglobal so that WordPress can use the environment variables.
