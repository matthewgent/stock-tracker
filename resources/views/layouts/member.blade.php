@extends('layouts.page')

@section('app-content')
    <board
        current-url="{{ url()->current() }}"
        home-url="{{ route('home') }}"
        plans-url="{{ route('plans') }}"
        wealth-url="{{ route('member.wealth') }}"
        account-url="{{ route('member.account') }}"
        assets-url="{{ route('member.assets') }}"
        debts-url="{{ route('member.debts') }}"
        currencies-url="{{ route('member.currencies') }}"
        settings-url="{{ route('member.settings') }}"
        app-name="{{ config('app.name') }}"
        :errors="{{ $errors }}"
        alert-type="{{ session('alertType') }}"
        alert-message="{{ session('alertMessage') }}"
        brand-src="{{ asset('/images/brand.svg') }}"
    >
        @yield('content')
    </board>
@endsection
