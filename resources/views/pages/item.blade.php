@extends('layouts.member', ['errors' => $errors])

@section('content')
    <item
        :item="{{ $item }}"
        :home-currency-id="{{ $homeCurrencyId }}"
        :currencies="{{ $currencies }}"
        :item-urls="{{ $itemUrls }}"
        :record-urls="{{ $recordUrls }}"
        csrf-token="{{ csrf_token() }}"
        :item-types="{{ $itemTypes }}"
        :has-premium="{{ $hasPremium }}"
        category-url="{{ $categoryUrl }}"
    ></item>
@endsection
