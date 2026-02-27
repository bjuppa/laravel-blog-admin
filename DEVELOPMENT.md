# Package development & testing

## Install for development within a Laravel app

1. Remove any existing directory `vendor/bjuppa/laravel-blog-admin`
2. Install the package from git using `composer install --prefer-source`
3. Run `cd vendor/bjuppa/laravel-blog-admin` and `composer install` to install dependencies within the package.

## Testing

Run `composer test` from the project directory to start the default test suite.

Run `composer report` to run tests with coverage reports.

Logs created during test runs can be found in `vendor/orchestra/testbench-core/laravel/storage/logs/`

If you want your own local configuration for phpunit,
copy the file `phpunit.xml.dist` to `phpunit.xml` and modify the latter to your needs.

## Following PSR2

This project can be checked against configured coding standards using `composer phpcs` from the project directory.

Automatic attempt to fix some reported coding standard violations can be run with
`./vendor/bin/phpcbf` from the project directory.

## Dependency version testing

- `composer update --prefer-lowest` can be used before running tests for testing backwards compatibility.
- `composer show -D -o` can be used to check how far behind latest version the currently installed dependencies are.
- `composer update` will install the latest versions of dependencies.

## Continuous integration

[GitHub Actions](https://github.com/bjuppa/laravel-blog-admin/actions) is set up to run tests on multiple versions of PHP and Laravel
whenever a git push or a PR is made.

## Compiled views

Running `composer clearCompiledViews` will delete the contents of
`vendor/orchestra/testbench-core/laravel/storage/framework/views/`
and this is automatically triggered when composer updates dependencies.

## Release new version

Releases are handled through [the GitHub releases interface](https://github.com/bjuppa/laravel-blog-admin/releases).
