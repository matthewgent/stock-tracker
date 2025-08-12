@extends('layouts.guest')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div class="plate plate-padding">
                    <div>
                        If you have any questions, suggestions or issues with {{ config('app.name') }}
                        then please let us know by sending an email to:
                    </div>
                    <h2 class="mt-3 text-primary text-center">
                        he<span class="visually-hidden">f</span>llo@bronz<span class="visually-hidden">dy</span>emountain.com
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endsection
