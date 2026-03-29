<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function notice(): View
    {
        $business = Business::query()->findOrFail(app('current_business_id'));
        $business->refreshSubscriptionStatus();

        return view('subscription.notice', [
            'business' => $business,
        ]);
    }
}
