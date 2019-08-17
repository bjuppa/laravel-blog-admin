# laravel-blog-admin

[![Build Status](https://travis-ci.org/bjuppa/laravel-blog-admin.svg?branch=master)](https://travis-ci.org/bjuppa/laravel-blog-admin)

Optional admin interface for [bjuppa/laravel-blog](https://packagist.org/packages/bjuppa/laravel-blog).

The admin pages of this packge depend on [kontenta/kontour](https://packagist.org/packages/kontenta/kontour),
a package providing admin area tool utilities for Laravel.

## Usage

1. Require the package (and the laravel-blog package too if you don't already have it):

   ```bash
   composer require bjuppa/laravel-blog-admin bjuppa/laravel-blog
   ```

   The package will automatically register itself.

2. Go through the [instructions for configuring `kontenta/kontour`](https://packagist.org/packages/kontenta/kontour).
   This is the bulk of the work needed to get the admin pages for your blog working,
   make sure you follow the instructions carefully!

3. Log in to your Kontour admin panel in your browser
   (usually at /admin unless you've changed that in the Kontour config).
   You should find one link to each of your Eloquent blogs in the Kontour menu.

4. By default, every logged in admin user has permissions to edit all the blogs.
   If you want to limit access, [Laravel gates or policies](https://laravel.com/docs/authorization)
   can be required for different actions and blogs by configuration in `config/blog.php`.

## Troubleshooting

If you can't log in to the Kontour admin area, look for solutions from
[`kontenta/kontour`](https://packagist.org/packages/kontenta/kontour).

If you can't see your blogs after you are successfully logged in,
make sure you have at least one Eloquent blog configured with
[`bjuppa/laravel-blog`](https://packagist.org/packages/bjuppa/laravel-blog).
Also make sure that your admin user has access rights through gates or policies
if they are configured in `config/blog.php`.
