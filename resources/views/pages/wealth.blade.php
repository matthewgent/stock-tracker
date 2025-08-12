@extends('layouts.member', ['title' => $title, 'errors' => $errors])

@section('content')
    <wealth
        assets-url="{{ route('member.assets') }}"
        debts-url="{{ route('member.debts') }}"
        contact-us-url="{{ route('contact-us') }}"
        settings-url="{{ route('member.settings') }}"
        :items="{{ $items }}"
        :item-types="{{ $itemTypes }}"
        :item-categories="{{ $itemCategories }}"
        :currencies="{{ $currencies }}"
        :home-currency-id="{{ $homeCurrencyId }}"
        :date-of-birth-string="{{ $dateOfBirth }}"
        :wealth-percentile-group="{{ $wealthPercentileGroup }}"
        :sovereign-state="{{ $sovereignState }}"
        :has-premium="{{ $hasPremium }}"
    ></wealth>
@endsection

