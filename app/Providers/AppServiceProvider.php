<?php

namespace App\Providers;

use App\Models\Member;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use const DIRECTORY_SEPARATOR as DS;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // helper functions to be available globally
        require_once __DIR__.DS.'..'.DS.'Helpers.php';

        // prevent default cashier migrations because they use the
        // table name "users" instead of "members"
        Cashier::ignoreMigrations();
        // activate VAT calculations (assumes Stripe tax details are up to date)
        Cashier::calculateTaxes();
    }

    public function boot(): void
    {
        // change cashier model from User to Member
        Cashier::useCustomerModel(Member::class);
        // enable calculation of taxes using Stripe tax system
        Cashier::calculateTaxes();
    }
}
