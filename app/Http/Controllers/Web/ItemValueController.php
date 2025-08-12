<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemValueRequest;
use App\Models\Item;
use App\Models\ItemValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ItemValueController extends Controller
{
    public function store(ItemValueRequest $request): RedirectResponse
    {
        $item = Item::query()->findOrFail($request->input('item_id'));

        self::verifyItemOwnership($item);

        // check for duplicate days
        $duplicateExists = $item->itemValues()->where('time', $request->input('date'))->exists();

        if ($duplicateExists) {
            $redirect = back()->with([
                'alertMessage' => 'A value record with this date already exists.',
                'alertType' => 'danger',
            ]);
        } else {
            $itemValue = new ItemValue();
            $itemValue->setAttribute('item_id', $item->getIdAttribute());

            self::fillItemValue($itemValue, $request);

            $redirect = back()->with([
                'alertMessage' => 'Successfully created value record.',
                'alertType' => 'success',
            ]);
        }

        return $redirect;
    }

    public function update(ItemValueRequest $request, ItemValue $itemValue): RedirectResponse
    {
        self::verifyItemOwnership($itemValue->getItem());

        // check for duplicate days
        $duplicateExists = $itemValue->getItem()
            ->itemValues()
            ->where('time', $request->input('date'))
            ->where('id', '<>', $itemValue->getIdAttribute())
            ->exists();

        if ($duplicateExists) {
            $redirect = back()->with([
                'alertMessage' => 'Another value record with this date already exists.',
                'alertType' => 'danger',
            ]);
        } else {
            self::fillItemValue($itemValue, $request);
            $redirect = back()->with([
                'alertMessage' => 'Successfully updated value record.',
                'alertType' => 'success',
            ]);
        }

        return $redirect;
    }

    public function destroy(ItemValue $itemValue): RedirectResponse
    {
        self::verifyItemOwnership($itemValue->getItem());

        $itemValue->delete();

        return back()->with([
            'alertMessage' => 'Successfully deleted value record.',
            'alertType' => 'success',
        ]);
    }

    private static function verifyItemOwnership(Item $item): void
    {
        if ($item->isNotOwned()) {
            abort(403);
        }
    }

    private static function fillItemValue(ItemValue $itemValue, Request $request): void
    {
        $itemValue->fill([
            'time' => $request->input('date'),
            'currency_id' => $request->input('currency'),
            'value' => $request->input('value'),
        ]);
        $itemValue->save();
    }
}
