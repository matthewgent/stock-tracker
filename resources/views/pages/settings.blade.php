@extends('layouts.member', ['errors' => $errors])

@section('content')
    <settings
        :currencies="{{ $currencies }}"
        :sovereign-states="{{ $sovereignStates }}"
        :home-currency="{{ $homeCurrency }}"
        :home-sovereign-state="{{ $homeSovereignState }}"
        :date-of-birth="{{ $dateOfBirth }}"
        update-settings-url="{{ $updateSettingsUrl }}"
        csrf-token="{{ $csrfToken }}"
        :has-premium="{{ json_encode($hasPremium) }}"
    ></settings>
@endsection
