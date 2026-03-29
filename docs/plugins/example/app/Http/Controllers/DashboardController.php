<?php

namespace Plugins\LoyaltyRewards;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('plugin-loyalty-rewards::dashboard');
    }
}
