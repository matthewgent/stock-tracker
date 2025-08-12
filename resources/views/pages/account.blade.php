@extends('layouts.member', ['errors' => $errors])

@section('content')
    <account
        csrf-token="{{ csrf_token() }}"
        update-member-url="{{ route('member.account.update') }}"
        destroy-member-url="{{ route('member.account.destroy') }}"
        logout-url="{{ route('logout') }}"
        cancel-premium-url="{{ route('member.premium.cancel') }}"
        email-address="{{ $emailAddress }}"
        :has-premium="{{ $hasPremium }}"
        :premium-period-end="{{ $premiumPeriodEnd }}"
        :premium-price="{{ $premiumPrice }}"
        :currencies="{{ $currencies }}"
        :on-grace-period="{{ $onGracePeriod }}"
        plans-url="{{ route('plans') }}"
    ></account>
@endsection
