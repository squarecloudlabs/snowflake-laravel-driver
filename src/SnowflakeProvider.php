<?php

namespace Snowflake\Laravel;

use Illuminate\Support\ServiceProvider;
use Snowflake\Laravel\Connect\Connection;
use Snowflake\Laravel\Connect\Connector;

class SnowflakeProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        Connection::resolverFor('snowflake', function ($connection, $database, $prefix, $config) {
            return new Connection($connection, $database, $prefix, $config);
        });
    }

    public function boot()
    {
        $this->app->bind('db.connector.snowflake', Connector::class);
    }
}
