@extends('layouts.guest')

@section('extra-javascript')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <premium
        :currencies="{{ $currencies }}"
        intent-secret="{{ $intentSecret }}"
        public-key="{{ $publicKey }}"
        payment-url="{{ route('member.premium.payment') }}"
        csrf-token="{{ csrf_token() }}"
        :errors="{{ $errors }}"
        initial-alert-type="{{ session('alertType') }}"
        initial-alert-message="{{ session('alertMessage') }}"
        email-address="{{ $emailAddress }}"
        :premium-price="{{ $premiumPrice }}"
    ></premium>
@endsection
