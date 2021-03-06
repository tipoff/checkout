<?php

declare(strict_types=1);

namespace Tipoff\Checkout\Tests;

use DrewRoberts\Blog\BlogServiceProvider;
use DrewRoberts\Media\MediaServiceProvider;
use Laravel\Nova\NovaCoreServiceProvider;
use Livewire\LivewireServiceProvider;
use Spatie\Fractal\FractalServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Tipoff\Addresses\AddressesServiceProvider;
use Tipoff\Authorization\AuthorizationServiceProvider;
use Tipoff\Checkout\CheckoutServiceProvider;
use Tipoff\Locations\LocationsServiceProvider;
use Tipoff\Seo\SeoServiceProvider;
use Tipoff\Statuses\StatusesServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;
use Tipoff\TestSupport\Providers\NovaPackageServiceProvider;

class TestCase extends BaseTestCase
{
    protected bool $stubNovaResources = false;

    protected function apiUrl(string $uri): string
    {
        $prefix = rtrim(config('tipoff.api.uri_prefix'), '/');

        return ltrim("{$prefix}/{$uri}", '/');
    }

    protected function webUrl(string $uri): string
    {
        $prefix = rtrim(config('tipoff.web.uri_prefix'), '/');

        return ltrim("{$prefix}/{$uri}", '/');
    }

    protected function getPackageProviders($app)
    {
        return [
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
            SupportServiceProvider::class,
            PermissionServiceProvider::class,
            AuthorizationServiceProvider::class,
            LivewireServiceProvider::class,
            AddressesServiceProvider::class,
            MediaServiceProvider::class,
            SeoServiceProvider::class,
            BlogServiceProvider::class,
            LocationsServiceProvider::class,
            StatusesServiceProvider::class,
            FractalServiceProvider::class,
            CheckoutServiceProvider::class,
        ];
    }
}
