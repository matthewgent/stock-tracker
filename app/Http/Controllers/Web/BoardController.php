<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\Member;
use Illuminate\View\View;

class BoardController extends Controller
{
    public function wealth(): View
    {
        $member = member();
        $member->updateStockPrices();
        $homeCurrencyId = $member->getCurrencyIdAttribute();

        $items = $member->items()
            ->with([
                'itemValues',
                'itemType.itemCategory',
                'stock',
                'stock.ticker',
                'stock.ticker.chartPrices',
                'stock.ticker.securityExchange',
                'stock.ticker.securityExchange.sovereignState',
                'stock.stockQuantities',
            ])
            ->get();

        $wealthPercentileGroup = $member->getSovereignState()
            ->wealthPercentileGroups()
            ->orderBy('version')
            ->with('wealthPercentiles')
            ->first();
        $this->addJsonViewVariable(
            'wealthPercentileGroup',
            $wealthPercentileGroup
        );

        $items->loadRequiredCurrencyIds();
        $items->addRequiredCurrencyId($homeCurrencyId);
        if ($wealthPercentileGroup !== null) {
            $items->addRequiredCurrencyId($wealthPercentileGroup->getCurrencyIdAttribute());
        }

        $currencies = Currency::query()
            ->with('chartRates')
            ->whereIntegerInRaw('id', $items->requiredCurrencyIds)
            ->get();

        $this->addJsonViewVariable('items', $items);

        $this->addJsonViewVariable('currencies', $currencies);
        $this->addJsonViewVariable('itemTypes', ItemType::query()->with('itemCategory')->get());
        $this->addJsonViewVariable('itemCategories', ItemCategory::query()->get());
        $this->addJsonViewVariable('homeCurrencyId', $homeCurrencyId);
        $this->addJsonViewVariable('dateOfBirth', $member->getDateOfBirthAttribute());
        $this->addJsonViewVariable('sovereignState', $member->sovereignState()->first());
        $this->addJsonViewVariable('hasPremium', $member->hasPremium());

        $this->addTitle('Wealth');
        $this->addDescription(
            'Displays all the statistics and charts relating to your overall wealth.'
        );
        return $this->view('pages.wealth');
    }

    public function assets(): View
    {
        $member = member();
        $member->updateStockPrices();

        $this->addStandardCategoryVariables($member);

        $this->addViewVariable('categoryName', 'asset');
        $this->addTitle('Assets');
        $this->addDescription(
            'Displays all the statistics and charts relating to your assets.'
        );

        return $this->view('pages.category');
    }

    public function debts(): View
    {
        $member = member();
        $member->updateStockPrices();

        $this->addStandardCategoryVariables($member);

        $this->addViewVariable('categoryName', 'debt');
        $this->addTitle('Debt');
        $this->addDescription(
            'Displays all the statistics and charts relating to your debts.'
        );

        return $this->view('pages.category');
    }

    public function currencies(): View
    {
        $member = member();
        $currencies = Currency::query()->with('latestRate')->orderBy('code')->get();
        $this->addJsonViewVariable('currencies', $currencies);
        $this->addViewVariable('homeCurrencyId', $member->getCurrencyIdAttribute());

        $this->addTitle('Currencies');
        $this->addDescription(
            'Displays your currency exposure as well as current and historic exchange rates.'
        );

        return $this->view('pages.currencies');
    }

    private function addStandardCategoryVariables(Member $member): void
    {
        $homeCurrencyId = $member->getCurrencyIdAttribute();

        $items = $member->items()
            ->with([
                'itemValues',
                'itemType.itemCategory',
                'stock',
                'stock.ticker',
                'stock.ticker.chartPrices',
                'stock.ticker.securityExchange',
                'stock.ticker.securityExchange.sovereignState',
                'stock.stockQuantities',
            ])
            ->get()
        ;
        $items->loadRequiredCurrencyIds();
        $items->addRequiredCurrencyId($homeCurrencyId);
        $this->addJsonViewVariable('items', $items);

        $currencies = Currency::query()
            ->with('chartRates')
            ->whereIntegerInRaw('id', $items->requiredCurrencyIds)
            ->get();
        $this->addJsonViewVariable('currencies', $currencies);

        $this->addJsonViewVariable('itemTypes', ItemType::query()->with('itemCategory')->get());
        $this->addViewVariable('homeCurrencyId', $homeCurrencyId);
        $this->addViewVariable('itemUrl', route('member.item.show', '#'));
        $this->addViewVariable('storeItemUrl', route('member.item.store'));
        $this->addJsonViewVariable('hasPremium', $member->hasPremium());
        $this->addViewVariable('apiToken', $member->getApiTokenAttribute());
        $this->addJsonViewVariable('stocksOwned', $member->stocks()->count());
    }
}
