<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;

// use Rinvex\Attributes\Models\Attribute;
// use Rinvex\Attributes\Models\Type\Boolean;
// use Rinvex\Attributes\Models\Type\Datetime;
// use Rinvex\Attributes\Models\Type\Integer;
// use Rinvex\Attributes\Models\Type\Varchar;
// use Rinvex\Attributes\Models\Type\Text;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // Attribute::typeMap([
        //     'varchar' => Varchar::class,
        //     'text' => Text::class,
        //     'boolean' => Boolean::class,
        //     'integer' => Integer::class,
        //     'datetime' => Datetime::class
        // ]);
    }
}
