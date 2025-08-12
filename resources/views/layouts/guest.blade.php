@extends('layouts.page')

@section('app-content')
    <top-bar
        home-url="{{ route('home') }}"
        plans-url="{{ route('plans') }}"
        account-url="{{ route(member() ? 'member.account' : 'login') }}"
        app-name="{{ config('app.name') }}"
        initially-minimized="0"
        include-burger-button="0"
        include-border-line="1"
        brand-src="{{ asset('/images/brand.svg') }}"
    ></top-bar>

    <main id="guest-wrapper">
        @yield('content')
    </main>

    <div id="footer">
        <div class="container h-100">
            <div class="row h-100 align-content-center">
                <div id="footer-col" class="col-12 text-center">
                    <a href="{{ route('contact-us') }}">Contact Us</a>
                    <span>|</span>
                    <a href="{{ route('legal') }}">Legal</a>
                </div>
            </div>
        </div>
    </div>
@endsection
