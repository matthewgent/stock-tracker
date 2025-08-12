<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Member;
use App\Models\SovereignState;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const VALIDATION_ARRAY = [
        'currency' => ['required', 'integer', 'exists:currencies,id'],
        'country' => ['required', 'integer', 'exists:sovereign_states,id'],
        'date_of_birth' => ['nullable', 'date'],
    ];

    public function index(): View
    {
        $member = member();

        $this->addTitle('Settings');
        $this->addDescription(
            'Displays all the changeable settings and preferences for use of the website.'
        );

        $this->addJsonViewVariable('currencies', Currency::query()->orderBy('name')->get());
        $this->addJsonViewVariable('sovereignStates', SovereignState::query()->orderBy('name')->get());
        $this->addJsonViewVariable('homeCurrency', $member->getCurrency());
        $this->addJsonViewVariable('homeSovereignState', $member->getSovereignState());
        $this->addJsonViewVariable('dateOfBirth', $member->getDateOfBirthAttribute());
        $this->addViewVariable('updateSettingsUrl', route('member.settings.update'));
        $this->addViewVariable('csrfToken', csrf_token());
        $this->addViewVariable('hasPremium', $member->hasPremium());

        return $this->view('pages.settings');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(self::VALIDATION_ARRAY);

        $dateOfBirth = $request->input('date_of_birth');
        if ($dateOfBirth === '') {
            $dateOfBirth = null;
        }

        $member = member();
        $member->setCurrencyIdAttribute($request->input('currency'));
        $member->setSovereignStateIdAttribute($request->input('country'));
        $member->setDateOfBirthAttribute($dateOfBirth);
        $member->save();

        return back()->with([
            'alertMessage' => 'Successfully updated settings.',
            'alertType' => 'success',
        ]);
    }
}
