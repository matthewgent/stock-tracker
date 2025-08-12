@extends('layouts.member', ['errors' => $errors])

@section('content')
    <category
        category-name="{{ $categoryName }}"
        :items="{{ $items }}"
        :item-types="{{ $itemTypes }}"
        :home-currency-id="{{ $homeCurrencyId }}"
        :currencies="{{ $currencies }}"
        item-url="{{ $itemUrl }}"
        plans-url="{{ route('plans') }}"
        store-item-url="{{ $storeItemUrl }}"
        csrf-token="{{ csrf_token() }}"
        :has-premium="{{ $hasPremium }}"
        api-token="{{ $apiToken }}"
        :free-stocks="{{ config('business.plans.basic.free_stocks') }}"
        :stocks-owned="{{ $stocksOwned }}"
    ></category>
@endsection
