# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **WordPress Multisite installation** for the Drolung International Foundation, a Buddhist charity organization. The project is currently set up as a local development environment using Local WP with the domain `drolung.local`.

The site includes:
- WordPress core files and standard WordPress directory structure
- Multiple themes (TwentyTwentyThree, TwentyTwentyFour, TwentyTwentyFive)
- Advanced Custom Fields (ACF) plugin for custom field management
- Static HTML mockups in the `mock1/` directory showing design variations

## Architecture

### WordPress Structure
- **WordPress Core**: Standard WordPress 6.x installation
- **Database**: Local MySQL database with credentials:
  - Database: `local`
  - Username: `root`
  - Password: `root`
  - Host: `localhost`
- **Multisite Configuration**: Subdomain installation enabled
  - Main domain: `drolung.local`
  - Multisite enabled with `MULTISITE`, `SUBDOMAIN_INSTALL` constants

### Key Directories
- `wp-content/themes/`: Contains active WordPress themes
- `wp-content/plugins/`: Contains installed plugins (currently ACF)
- `wp-content/uploads/`: Media uploads directory
- `wp-admin/`: WordPress admin interface files
- `wp-includes/`: WordPress core functionality files
- `mock1/`: Static HTML mockups with design variations for the charity site

### Installed Plugins
- **Advanced Custom Fields (ACF)**: Professional WordPress plugin for custom fields
  - Version: WP Engine ACF
  - Composer autoloading enabled with PSR-4 namespace `ACF\`
  - Includes REST API integration and Gutenberg block bindings

### Active Themes
- **TwentyTwentyFive**: Latest WordPress default theme
- **TwentyTwentyFour**: Previous WordPress default theme
- **TwentyTwentyThree**: Earlier WordPress default theme

## Development Workflow

### Local Development Environment
This is a **Local WP** installation for local development:

**To start development:**
1. Start Local WP application
2. Start the `drolung` site in Local WP
3. Access the site at `https://drolung.local`
4. WordPress admin available at `https://drolung.local/wp-admin`

**Common Development Commands:**
Since this is a WordPress site without a build system, most development happens through:
- WordPress admin interface for content management
- File editing for theme/plugin customization
- Database operations through phpMyAdmin or WP-CLI (if available)

### WordPress-specific Commands
No package.json or build commands detected. This is a standard WordPress installation without a custom build process.

**For WordPress development:**
- Use WordPress admin for content and configuration
- Edit theme files directly in `wp-content/themes/`
- Customize plugins in `wp-content/plugins/`
- Use WordPress hooks and filters for functionality
- Follow WordPress coding standards

### Database Configuration
Environment is set to 'local' with debug mode disabled in production:
```php
define('WP_ENVIRONMENT_TYPE', 'local');
define('WP_DEBUG', false); // Currently disabled
```

## Code Conventions

### WordPress Standards
- Follow WordPress PHP Coding Standards
- Use WordPress hooks (actions/filters) for customizations
- Implement proper escaping and sanitization
- Use WordPress APIs for database interactions

### ACF Integration
- Custom fields managed through ACF admin interface
- Fields can be exported/imported via JSON
- REST API endpoints available for ACF fields
- Gutenberg block support enabled

### Theme Development
- Use WordPress template hierarchy
- Leverage block themes (JSON-based styling)
- Follow accessibility best practices
- Implement responsive design patterns

## Security Configuration

- WordPress security keys configured in wp-config.php
- Multisite security considerations enabled
- Standard WordPress file permissions apply
- Debug mode currently disabled for security

## Static Mockups (mock1/)

The `mock1/` directory contains static HTML prototypes showing different design approaches:
- Multiple index variations (index.html through index5.html)
- Complete responsive designs with inline CSS
- Buddhist charity theme with appropriate imagery
- No build process - direct HTML/CSS/JS files

These mockups serve as design references for WordPress theme development.

## Important Notes

- This is a **local development environment only**
- Multisite installation requires careful URL handling
- ACF fields should be managed through the WordPress admin
- Static mockups in `mock1/` are separate from WordPress functionality
- No automated testing or CI/CD pipeline detected
- Standard WordPress backup and security practices apply