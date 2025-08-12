<?php

namespace App\Models;

use App\Services\StripeClient;
use App\Utilities\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;

use function Illuminate\Events\queueable;

class Member extends Authenticatable
{
    use Notifiable, Billable;

    protected $fillable = [
        'currency_id',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'currency_id',
        'sovereign_state_id',
    ];

    protected $with = ['currency', 'sovereignState'];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->makeHidden(['created_at', 'updated_at']);
    }

    protected static function booted()
    {
        // synchronize member details with Stripe upon changing
        static::updated(queueable(function ($customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }

    // Getters

    public function getIdAttribute(): int
    {
        return intval($this->attributes['id']);
    }

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    public function getSovereignStateIdAttribute(): int
    {
        return intval($this->attributes['sovereign_state_id']);
    }

    public function getDateOfBirthAttribute(): ?string
    {
        return $this->attributes['date_of_birth'];
    }

    public function getEmailAttribute(): string
    {
        return $this->attributes['email'];
    }

    public function getEmailVerifiedAtAttribute(): ?Carbon
    {
        $value = $this->attributes['email_verified_at'];
        if ($value !== null) {
            $value = new Carbon($value);
        }
        return $value;
    }

    public function getPasswordAttribute(): string
    {
        return $this->attributes['password'];
    }

    public function getApiTokenAttribute(): string
    {
        return $this->attributes['api_token'];
    }

    // Setters

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = $value;
    }

    public function setDateOfBirthAttribute(string $value = null): void
    {
        $this->attributes['date_of_birth'] = $value;
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setCurrencyIdAttribute(int $value): void
    {
        $this->attributes['currency_id'] = $value;
    }

    public function setSovereignStateIdAttribute(int $value): void
    {
        $this->attributes['sovereign_state_id'] = $value;
    }

    // Relationships

    public function administrator(): HasOne
    {
        return $this->hasOne(Administrator::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function sovereignState(): BelongsTo
    {
        return $this->belongsTo(SovereignState::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function stocks(): HasManyThrough
    {
        return $this->hasManyThrough(Stock::class, Item::class);
    }

    public function getCurrency(): Currency
    {
        return $this->currency()->first();
    }

    public function getSovereignState(): SovereignState
    {
        return $this->sovereignState()->first();
    }

    // Business Logic

    public function isAdministrator(): bool
    {
        return Administrator::query()
            ->where('member_id', $this->id)
            ->exists();
    }

    public function getAssets(): array
    {
        return $this->getItemsByCategory(1);
    }

    public function getDebts(): array
    {
        return $this->getItemsByCategory(2);
    }

    private function getItemsByCategory(int $categoryId): array
    {
        $items = $this->items()->get();
        $types = ItemType::query()
            ->with('itemCategory')
            ->where('item_category_id', $categoryId)
            ->orderBy('name')
            ->pluck('name');
        $array = [];

        foreach ($types as $type) {
            $filtered = $items->filter(function ($value) use ($type) {
                return $value->itemType->name === $type;
            });
            $array[$type] = array_values($filtered->toArray());
        }

        return $array;
    }

    public function hasPremium(): bool
    {
        $productId = config('services.stripe.product_id');
        return $this->subscribedToProduct($productId);
    }

    public function onGracePeriod(): bool
    {
        $onGracePeriod = false;
        $subscription = $this->subscription();
        if ($subscription !== null) {
            $onGracePeriod = $subscription->onGracePeriod();
        }
        return $onGracePeriod;
    }

    public function premiumPrice(): ?Subscription
    {
        $price = null;
        if ($this->subscribed()) {
            $priceId = $this->subscription()->stripe_price;
            $stripe = new StripeClient;
            $price = $stripe->getPremiumPrice($priceId);
        }
        return $price;
    }

    public function premiumPeriodEnd(): ?Carbon
    {
        $date = null;
        if ($this->hasPremium()) {
            $date = $this->subscription()->asStripeSubscription()->current_period_end;
            $date = new Carbon($date);
        }
        return $date;
    }

    public function updateStockPrices(): void
    {
        $stocks = $this->stocks()->get();
        $stocks->each(function (Stock $stock) {
            $stock->getTicker()->updatePrices();
        });
    }
}
