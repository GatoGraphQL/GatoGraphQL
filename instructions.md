# Gato GraphQL AI Development Guidelines

## Project Overview

Gato GraphQL is a WordPress plugin that provides a GraphQL server for WordPress, allowing to query, transform, and mutate any piece of data from the WordPress site, via GraphQL queries. It supports 100+ WordPress plugins through its integration system.

## Development Standards

### Project Organization

### Code Organization

- Main plugin file: `gatographql.php`
- Source code: `src/`
- Tests: `tests/`

### Coding Standards

- Follow Coding Standards (WPCS) as defined in `.cursor/rules/php-wordpress.mdc`
- PHPStan for static analysis
- PHPCS for code style enforcement
- Use type hints and PHPDoc blocks for all new code
- Maintain PHPStan baseline in `phpstan-baseline.neon`
- Maintain PHPCS baseline in `phpcs.baseline.xml`

### Version Control

- Branch naming: `feature/`, `fix/`, `enhancement/` prefixes
- Commit messages should reference ticket numbers when applicable
- Keep commits atomic and focused

### Testing

- PHPUnit for unit testing
- Test new plugin integrations thoroughly
- Test against supported WordPress versions (latest - 2)
- Test against supported WooCommerce versions when applicable

## Resources

### Internal

- `readme.txt` - Plugin documentation

### External

- Gato GraphQL Documentation:
  - [Gato GraphQL Guides](https://gatographql.com/guides)
  - [Gato GraphQL Extensions reference](https://gatographql.com/extensions-reference)
  - [Gato GraphQL Queries library](https://gatographql.com/library)
  - [Gato GraphQL Schema tutorial](https://gatographql.com/tutorial)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/)
