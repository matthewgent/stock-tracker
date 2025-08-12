@extends('layouts.guest')

@section('content')
<div id="register" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- section temporarily withdrawn -->
                        <div class="form-group row d-none">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Country') }}</label>

                            <div class="col-md-7">
                                <select id="country" class="form-control @error('country') is-invalid @enderror" name="country">
                                    @foreach($sovereignStates as $sovereignState)
                                        <option value="{{ $sovereignState->id }}" @if($sovereignState->id === $defaultSovereignState) selected @endif>{{ $sovereignState->name }}</option>
                                    @endforeach
                                </select>

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <label for="currency" class="col-md-4 col-form-label text-md-right">{{ __('Preferred currency') }}</label>

                            <div class="col-md-7">
                                <select id="currency" class="form-control @error('currency') is-invalid @enderror" name="currency">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @if($currency->id === $defaultCurrency) selected @endif>{{ $currency->code }} - {{ $currency->name }}</option>
                                    @endforeach
                                </select>

                                @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-7">

                                <register-password
                                    is-invalid="@error('password') 1 @enderror"
                                ></register-password>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <h5 class="mb-2">
                                By clicking {{ __('Register') }} below, you agree to the <a class="text-link" target="_blank" href="{{ route('legal') }}">terms and conditions</a>.
                            </h5>
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="button-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
