<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function show(): View
    {
        $member = member();
        $this->addTitle('My Account');
        $this->addDescription(
            'Show the details of your '.config('app.name').' account.'
        );
        $subscription = $member->premiumPrice();
        $this->addViewVariable('emailAddress', $member->getEmailAttribute());
        $this->addJsonViewVariable('hasPremium', $member->hasPremium());
        $this->addJsonViewVariable('premiumPeriodEnd', $member->premiumPeriodEnd());
        $this->addJsonViewVariable('premiumPrice', $subscription);
        $this->addJsonViewVariable('currencies', $subscription ? [$subscription->currency] : []);
        $this->addJsonViewVariable('onGracePeriod', $member->onGracePeriod());
        return $this->view('pages.account');
    }

    public function update(Request $request): RedirectResponse
    {
        $member = member();

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('members')->ignore($member)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $member->setEmailAttribute($email);
        if ($password !== null) {
            $member->setPasswordAttribute($password);
        }
        $member->save();

        return back()->with([
            'alertMessage' => 'Successfully updated account.',
            'alertType' => 'success',
        ]);
    }

    public function destroy(): RedirectResponse
    {
        $member = member();
        Auth::logout();
        $member->delete();

        return redirect(route('home'))->with([
            'alertMessage' => 'Successfully created account.',
            'alertType' => 'success',
        ]);
    }
}
