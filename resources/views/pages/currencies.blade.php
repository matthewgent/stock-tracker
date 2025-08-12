@extends('layouts.member', ['errors' => $errors])

@section('content')
    <currencies
        :currencies="{{ $currencies }}"
        :home-currency-id="{{ $homeCurrencyId }}"
    ></currencies>
@endsection
