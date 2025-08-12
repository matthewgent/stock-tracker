<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\Stock;
use App\Models\Member;
use App\Models\Ticker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ItemController extends Controller
{
    private const VIEW_NAME = 'pages.item';

    public static function maximumItemsReached(Member $member): bool
    {
        $maximumItems = config('business.plans.basic.free_items');

        if (!$member->hasPremium() and $member->items()->count() >= $maximumItems) {
            $breached = true;
        } else {
            $breached = false;
        }

        return $breached;
    }

    public static function maximumItemsReachedResponse(): RedirectResponse
    {
        $maximumItems = config('business.plans.basic.free_items');

        return back()->with([
            'alertMessage' => 'Premium subscription required to create more than ' . $maximumItems . ' assets or debts.',
            'alertType' => 'danger',
        ]);
    }

    public static function maximumStocksReached(Member $member): bool
    {
        $maximumStocks = config('business.plans.basic.free_stocks');

        if (!$member->hasPremium() and $member->stocks()->count() >= $maximumStocks) {
            $breached = true;
        } else {
            $breached = false;
        }

        return $breached;
    }

    public static function maximumStocksReachedResponse(): RedirectResponse
    {
        $maximumStocks = config('business.plans.basic.free_stocks');
        $stockWord = $maximumStocks === 1 ? 'stock' : 'stocks';

        return back()->with([
            'alertMessage' => 'Premium subscription required to create more than ' . $maximumStocks . ' '.$stockWord.'.',
            'alertType' => 'danger',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ticker' => [
                'required_without_all:name,category',
                'integer',
                'exists:tickers,id',
            ],
            'name' => [
                'required_without:ticker',
                'string',
                'max:255',
            ],
            'category' => [
                'required_without:ticker',
                'integer',
                'exists:item_types,id',
            ],
        ]);

        $member = member();

        if (self::maximumItemsReached($member)) {
            return self::maximumItemsReachedResponse();
        }

        $isStock = $request->exists('ticker');
        $categoryId = $isStock ? 10 : $request->input('category');
        $category = ItemType::query()->findOrFail($categoryId);

        if ($isStock) {
            if (self::maximumStocksReached($member)) {
                return self::maximumStocksReachedResponse();
            }
            $ticker = Ticker::query()->findOrFail($request->input('ticker'));
            if (!$ticker->pricesExist()) {
                Log::warning('Failed to retrieve prices for stock.', [
                    'ticker_id' => $ticker->getIdAttribute(),
                    'member_id' => $member->getIdAttribute(),
                ]);
                return back()->with([
                    'alertMessage' => "Unfortunately we couldn't provide any financial data for this ".$category->getNameAttribute().".",
                    'alertType' => 'danger',
                ]);
            }
        }

        $item = new Item();
        $item->fill([
            'member_id' => $member->getIdAttribute(),
            'name' => $request->input('name'),
            'item_type_id' => $categoryId,
        ]);
        $item->save();

        if ($isStock) {
            $ticker = Ticker::query()->findOrFail($request->input('ticker'));
            $stock = new Stock;
            $stock->fill([
                'item_id' => $item->getIdAttribute(),
                'ticker_id' => $ticker->getIdAttribute(),
            ]);
            $stock->save();
        }

        $category = $item->getType()->getCategory()->getNameAttribute();
        return back()->with([
            'alertMessage' => 'Successfully created '.$category.'.',
            'alertType' => 'success',
        ]);
    }

    public function show(Item $item): View
    {
        self::verifyItemOwnership($item);

        if ($item->getClass() === 1) {
            $item->getStock()->getTicker()->updatePrices();
        }

        return $this->itemView($item);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        self::verifyItemOwnership($item);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);
        $name = $request->input('name');

        $item->update([
            'name' => $name,
        ]);

        $category = $item->getType()->getCategory()->getNameAttribute();
        return back()->with([
            'alertMessage' => 'Successfully updated '.$category.'.',
            'alertType' => 'success',
        ]);
    }

    public function destroy(Item $item): RedirectResponse
    {
        self::verifyItemOwnership($item);
        $categoryName = $item->getType()->getCategory()->getNameAttribute();
        $item->delete();

        return redirect(route('member.'.$categoryName.'s'))->with([
            'alertMessage' => 'Successfully deleted '.$categoryName.'.',
            'alertType' => 'success',
        ]);
    }

    protected function itemView(Item $item): View
    {
        $member = member();
        $homeCurrencyId = $member->getCurrencyIdAttribute();
        $item->load([
            'itemValues',
            'itemType.itemCategory',
            'stock',
            'stock.ticker',
            'stock.ticker.chartPrices',
            'stock.ticker.securityExchange',
            'stock.ticker.securityExchange.sovereignState',
            'stock.stockQuantities',
        ]);
        $this->addJsonViewVariable(
            'item',
            $item
        );

        $this->addViewVariable('categoryUrl', route('member.'.$item->itemType->itemCategory->name.'s'));

        $currencies = Currency::query()->with('chartRates')->get();
        $this->addJsonViewVariable('currencies', $currencies);
        $this->addViewVariable('homeCurrencyId', $homeCurrencyId);
        $this->addJsonViewVariable('itemUrls', [
            'update' => route('member.item.update', $item->getIdAttribute()),
            'destroy' => route('member.item.destroy', $item->getIdAttribute()),
        ]);
        if ($item->getStock() !== null) {
            $this->addJsonViewVariable('recordUrls', [
                'store' => route('member.stock-quantity.store'),
                'update' => route('member.stock-quantity.update', '#'),
                'destroy' => route('member.stock-quantity.destroy', '#'),
            ]);
        } else {
            $this->addJsonViewVariable('recordUrls', [
                'store' => route('member.item-value.store'),
                'update' => route('member.item-value.update', '#'),
                'destroy' => route('member.item-value.destroy', '#'),
            ]);
        }

        $category = $item->itemType->itemCategory;
        $this->addJsonViewVariable('itemTypes', $category->itemTypes()->orderBy('name')->get());
        $this->addJsonViewVariable('hasPremium', $member->hasPremium());

        $this->addTitle($item->getName());
        $this->addDescription(
            'Displays all the statistics and charts relating to a specific asset or debt.'
        );

        return $this->view(self::VIEW_NAME);
    }

    public static function verifyItemOwnership(Item $item): void
    {
        if ($item->isNotOwned()) {
            abort(403);
        }
    }
}
