<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\StripeClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Exception;

class PremiumController extends Controller
{
    public function show(): View
    {
        $member = member();
        $setupIntent = $member->createSetupIntent();
        $stripe = new StripeClient;

        $this->addViewVariable('intentSecret', $setupIntent->client_secret);
        $this->addViewVariable('publicKey', config('services.stripe.key'));
        $this->addViewVariable('emailAddress', $member->getEmailAttribute());
        $subscription = $stripe->getPremiumPrice();
        $this->addJsonViewVariable('premiumPrice', $subscription);
        $this->addJsonViewVariable('currencies', [$subscription->currency]);

        $this->addTitle('Purchase premium');
        $this->addDescription(
            'Purchase the premium plan for your '.config('app.name').' account.'
        );

        return $this->view('pages.premium');
    }

    public function processPayment(Request $request): RedirectResponse
    {
        $member = member();
        $methodId = $request->input('payment_method_id');
        $priceId = config('services.stripe.price_id');

        try {
            if ($member->hasPremium()) {
                $redirect = back()->with([
                    'alertMessage' => 'You are already subscribed to premium.',
                    'alertType' => 'danger',
                ]);
            } else {
                if ($methodId === null) {
                    throw new Exception('No payment method ID present.');
                }
                $member->newSubscription('default', $priceId)->create($methodId);
                $redirect = redirect()->route('member.account')->with([
                    'alertMessage' => 'Successfully subscribed to premium.',
                    'alertType' => 'success',
                ]);
            }
        } catch (Exception $e) {
            $message = 'An error occurred when creating your subscription. Please contact us.';
            Log::error($message, [
                'message' => $e->getMessage(),
                'member_id' => $member->getIdAttribute(),
                'payment_method_id' => $methodId,
                'price_id' => $priceId,
            ]);
            $redirect = back()->with([
                'alertMessage' => $message,
                'alertType' => 'danger',
            ]);
        }

        return $redirect;
    }

    public function cancel(): RedirectResponse
    {
        $member = member();
        try {
            $member->subscription()->cancel();
            $redirect = back()->with([
                'alertMessage' => 'Successfully cancelled premium subscription.',
                'alertType' => 'success',
            ]);
        } catch (Exception $e) {
            Log::error('Failed to cancel member subscription.', [
                'message' => $e->getMessage(),
                'member_id' => $member->getIdAttribute(),
            ]);
            $redirect = back()->with([
                'alertMessage' => 'Failed to cancel premium subscription. Please contact us.',
                'alertType' => 'danger',
            ]);
        }

        return $redirect;
    }
}
