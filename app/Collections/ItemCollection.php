<?php
namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ItemCollection extends Collection
{
    public array $requiredCurrencyIds = array();

    public function addRequiredCurrencyId(int $id): void
    {
        if (!in_array($id, $this->requiredCurrencyIds)) {
            $this->requiredCurrencyIds[] = $id;
        }
    }

    public function loadRequiredCurrencyIds(): void
    {
        $ids = array();
        foreach ($this as $item) {
            if ($item->stock !== null) {
                $currencyId = $item->stock->ticker->securityExchange->getCurrencyIdAttribute();
                $this->addRequiredCurrencyId($currencyId);
                if (!in_array($currencyId, $ids)) {
                    $ids[] = $currencyId;
                }
            } else {
                foreach ($item->itemValues as $itemValue) {
                    $this->addRequiredCurrencyId($itemValue->getCurrencyIdAttribute());
                }
            }
        }
        sort($this->requiredCurrencyIds);
    }
}
