<?php

namespace App\Providers;
use App\Models\TypesFuel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        // تمرير أنواع المحروقات إلى القائمة الجانبية لبناء روابط "حركة الوارد والصادر" ديناميكياً
        View::composer('layout.main-sidebar', function ($view) {
            $view->with('sidebarFuelTypes', TypesFuel::orderBy('id')->get());
        });
    }
}
