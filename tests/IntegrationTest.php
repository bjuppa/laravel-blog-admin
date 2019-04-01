<?php

namespace Bjuppa\LaravelBlogAdmin\Tests;

use Orchestra\Testbench\TestCase;

abstract class IntegrationTest extends TestCase
{
    /**
     * Setup the test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Tear down the test case.
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Get the service providers for the package.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Bjuppa\LaravelBlogAdmin\BlogAdminServiceProvider',
            'Bjuppa\LaravelBlog\BlogServiceProvider',
            'Kontenta\Kontour\Providers\KontourServiceProvider',
        ];
    }

    /**
     * Configure the environment.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        foreach ($this->extraConfigs() as $key => $value) {
            $app['config']->set($key, $value);
        }
    }

    /**
     * Set up database for testing.
     */
    protected function prepareDatabase()
    {
        // Make sure sqlite database file exists
        if ($this->app->config->get('database.default') === 'sqlite') {
            touch($this->app->config->get('database.connections.sqlite.database'));
        }

        // Run Laravel's default migrations for user table etc
        $this->loadLaravelMigrations($this->app->config->get('database.default'));

        // Run any migrations registered in service providers
        $this->loadRegisteredMigrations();

        $this->withFactories(__DIR__ . '/../database/factories');
        $this->withFactories(__DIR__ . '/../vendor/bjuppa/laravel-blog/database/factories');
    }

    /**
     * Override this method to set configuration values in your test class
     *
     * @return array of config keys (in dot-notation) and values
     */
    protected function extraConfigs(): array
    {
        return [];
    }

    /**
     * Run the migrations registered in the app
     */
    protected function loadRegisteredMigrations()
    {
        $this->artisan('migrate');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
}
