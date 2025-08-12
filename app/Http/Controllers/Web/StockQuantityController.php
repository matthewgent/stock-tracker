<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockQuantity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function abort;
use function back;

class StockQuantityController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'stock_id' => [
                'required',
                'integer',
                'exists:stocks,id',
            ],
            'quantity' => [
                'required',
                'numeric',
                'gte:0',
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:1970-01-01',
                'before_or_equal:today'
            ],
        ]);

        $stock = Stock::query()->findOrFail($request->input('stock_id'));
        self::verifyStockOwnership($stock);

        $duplicateExists = $stock->stockQuantities()->where('time', $request->input('date'))->exists();

        if ($duplicateExists) {
            $redirect = back()->with([
                'alertMessage' => 'A share record with this date already exists.',
                'alertType' => 'danger',
            ]);
        } else {
            $item = new StockQuantity();
            $item->fill([
                'stock_id' => $stock->getIdAttribute(),
                'quantity' => $request->input('quantity'),
                'time' => $request->input('date'),
            ]);
            $item->save();

            $redirect = back()->with([
                'alertMessage' => 'Successfully added quantity.',
                'alertType' => 'success',
            ]);
        }

        return $redirect;
    }

    public function update(Request $request, StockQuantity $stock_quantity): RedirectResponse
    {
        $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gte:0',
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:1970-01-01',
                'before_or_equal:today'
            ],
        ]);

        $stock = $stock_quantity->getStock();
        self::verifyStockOwnership($stock);

        $duplicateExists = $stock->stockQuantities()
            ->where('time', $request->input('date'))
            ->where('id', '<>', $stock_quantity->getIdAttribute())
            ->exists();

        if ($duplicateExists) {
            $redirect = back()->with([
                'alertMessage' => 'Another share record with this date already exists.',
                'alertType' => 'danger',
            ]);
        } else {
            $stock_quantity->update([
                'quantity' => $request->input('quantity'),
                'time' => $request->input('date'),
            ]);

            $redirect = back()->with([
                'alertMessage' => 'Successfully updated quantity.',
                'alertType' => 'success',
            ]);
        }

        return $redirect;
    }

    public function destroy(StockQuantity $stock_quantity): RedirectResponse
    {
        self::verifyStockOwnership($stock_quantity->getStock());

        $stock_quantity->delete();

        return back()->with([
            'alertMessage' => 'Successfully deleted quantity record.',
            'alertType' => 'success',
        ]);
    }

    private static function verifyStockOwnership(Stock $stock): void
    {
        if ($stock->getItem()->isNotOwned()) {
            abort(403);
        }
    }
}
