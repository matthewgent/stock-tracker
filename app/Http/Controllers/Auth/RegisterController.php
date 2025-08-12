<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\SovereignState;
use App\Providers\RouteServiceProvider;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\View\View;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected string $redirectTo = RouteServiceProvider::HOME;

   public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(): View
    {
        $this->addTitle('Register');
        $this->addDescription(
            'Use this form to create a new '.config('app.name').' account.'
        );

        $currencies = Currency::query()->orderBy('code')->get();
        $this->addViewVariable('currencies', $currencies);
        $this->addViewVariable('defaultCurrency', 1);

        $sovereignStates = SovereignState::query()->orderBy('name')->get();
        $this->addViewVariable('sovereignStates', $sovereignStates);
        $this->addViewVariable('defaultSovereignState', 239);

        $now = new Carbon;
        $now->subYears(18);
        $this->addViewVariable('defaultDateOfBirth', $now->toDateString());

        return $this->view('pages.auth.register');
    }

    protected function validator(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members'],
            'password' => ['required', 'string', 'min:8'],
            //'currency' => ['required', 'integer', 'exists:currencies,id'],
            //'country' => ['required', 'integer', 'exists:sovereign_states,id'],
        ]);
    }

    protected function create(array $data): Member
    {
        $member = new Member;
        $member->currency_id = 4; // TEMPORARY: usually this would be $data['currency']
        $member->sovereign_state_id = 237; // TEMPORARY: usually this would be $data['country']
        $member->email = $data['email'];
        $member->password = $data['password'];
        $member->api_token = Str::random(80);
        $member->save();

        return $member;
    }


}
