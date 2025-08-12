@extends('layouts.guest')

@section('content')
    <div id="how-it-works" class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <div class="number">1.</div>
                    <div>Add each of your assets and debts, such as bank accounts, mortgages and stocks. There are categories to help you with this.</div>
                </div>
            </div>
            <div class="col-12 col-lg-6 screenshot">
                <img src="{{ asset('images/screenshots/how-it-works/assets.png') }}" alt="assets">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <div class="number">2.</div>
                    <div>
                        Update the value of each asset or debt periodically (we recommend monthly).
                        Stock prices and exchange rates are all updated automatically for you.
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 screenshot">
                <img src="{{ asset('images/screenshots/how-it-works/stock.png') }}" alt="stock">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <div class="number">3.</div>
                    <div>Now you can see all your statistics such as your net worth or your wealth distribution shown in the pie chart.</div>
                </div>
            </div>
            <div class="col-12 col-lg-6 screenshot">
                <img src="{{ asset('images/screenshots/how-it-works/pie-chart.png') }}" alt="pie chart">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <div class="number">4.</div>
                    <div>Determine your monthly wealth gain and see how it's used to project your net worth when you reach retirement or any other age.</div>
                </div>
            </div>
            <div class="col-12 col-lg-6 screenshot">
                <img src="{{ asset('images/screenshots/how-it-works/projector.png') }}" alt="projector">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <div class="number">5.</div>
                    <div>Using official wealth data, you can also see what wealth percentile you fall under for your country.</div>
                </div>
            </div>
            <div class="col-12 col-lg-6 screenshot">
                <img src="{{ asset('images/screenshots/how-it-works/comparator.png') }}" alt="wealth percentile">
            </div>
        </div>
    </div>
@endsection
