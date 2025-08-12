@extends('layouts.guest')

@section('content')
    <plan
        :currencies="{{ $currencies }}"
        :premium-price="{{ $premiumPrice }}"
        :is-member="{{ $isMember }}"
        :has-premium="{{ $hasPremium }}"
        :free-items="{{ $freeItems }}"
        :free-stocks="{{ $freeStocks }}"
        login-url="{{ route('login') }}"
        premium-url="{{ route('member.premium') }}"
        account-url="{{ route('member.account') }}"
        register-url="{{ route('register') }}"
    ></plan>
@endsection
