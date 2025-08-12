@extends('layouts.guest')

@section('content')
    <div id="home">
        <div id="background"></div>
        <div class="container">
            <div class="spacer">
                <h1 id="subtitle">If you're serious about money</h1>
                <h1 id="title">Track your wealth.</h1>
            </div>

            <div class="row spacer">
                <div class="col-12 col-md-8 col-xl-6">
                    <div class="faded_box_theme">
                        <p>Bronze Mountain is a website that tracks your assets and debts so you can measure your wealth and progress.</p>
                        <div class="action-buttons mt-4 row">
                            <div class="col-6">
                                <a href="{{ route('register') }}" class="button-gold">Sign up for free</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('plans') }}" class="button-background">See Plans</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row spacer align-items-stretch g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="faded_box_black h-100 text-center">
                        <div class="tile_icon"><i class="fas fa-chart-pie"></i></div>
                        <h3>Categorize all your assets and debts to get a clear view of where your wealth lies.</h3>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="faded_box_black h-100 text-center">
                        <div class="tile_icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Track the prices of over 150,000 worldwide stocks and shares.</h3>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="faded_box_black h-100 text-center">
                        <div class="tile_icon"><i class="fas fa-hand-holding-usd"></i></div>
                        <h3>Have foreign currencies converted into the home currency of your choice.</h3>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="faded_box_black h-100 text-center">
                        <div class="tile_icon"><i class="fas fa-user-friends"></i></div>
                        <h3>See how your wealth compares to others in your country.</h3>
                    </div>
                </div>
            </div>

            <div class="spacer faded_box_black">
                <screenshot-carousel
                    :images="{{ $images }}"
                ></screenshot-carousel>
            </div>

            <div class="action-buttons spacer row justify-content-center g-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <a href="{{ route('register') }}" class="button-gold">Sign up for free</a>
                </div>
                <div class="col-12 col-md-5 col-lg-4">
                    <a href="{{ route('plans') }}" class="button-background">See Plans</a>
                </div>
            </div>
        </div>
    </div>
@endsection
