<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\StripeClient;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function home(): View
    {
        $this->addTitle('Home');
        $this->addDescription(
            'Bronze Mountain provides free online wealth tracking and management software to help people better understand and prepare their finances for the future.'
        );
        $this->addJsonViewVariable('images', [
            asset('/images/screenshots/stock.png'),
            asset('/images/screenshots/assets.png'),
            asset('/images/screenshots/wealth.png'),
            asset('/images/screenshots/debts.png'),
            asset('/images/screenshots/exchange-rates.png'),
        ]);
        return $this->view('pages.home');
    }

    public function contactUs(): View
    {
        $this->addTitle('Contact Us');
        $this->addDescription(
            'Use this form to send '.config('app.name').' a message. It can be used for queries, complaints, suggestions, tech support or anything else.'
        );
        return $this->view('pages.contact-us');
    }

    public function legal(): View
    {
        $this->addTitle('Legal');
        $this->addDescription(
            'Displays all the terms and conditions related to use of the '.config('app.name').' website.'
        );
        return $this->view('pages.legal');
    }

    public function plans(): View
    {
        $member = member();
        $stripe = new StripeClient;
        $subscription = $stripe->getPremiumPrice();
        $this->addJsonViewVariable('premiumPrice', $subscription);
        $this->addJsonViewVariable('currencies', [$subscription->currency]);
        $this->addJsonViewVariable('isMember', boolval($member));
        $this->addJsonViewVariable('hasPremium', $member && $member->hasPremium());
        $this->addJsonViewVariable('freeItems', config('business.plans.basic.free_items'));
        $this->addJsonViewVariable('freeStocks', config('business.plans.basic.free_stocks'));
        $this->addTitle('Plans');
        $this->addDescription(
            'Choose a plan for your '.config('app.name').' account.'
        );
        return $this->view('pages.plans');
    }

    public function howItWorks(): View
    {
        $this->addTitle('How it works');
        $this->addDescription('Explanation of how to use the '.config('app.name').' application.');

        return $this->view('pages.how-it-works');
    }
}
