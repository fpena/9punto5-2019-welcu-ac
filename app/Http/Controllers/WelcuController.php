<?php

namespace App\Http\Controllers;

use App\Enums\WelcuEventType;
use Gentor\ActiveCampaign\Facades\ActiveCampaign;
use Illuminate\Http\Request;

class WelcuController extends Controller
{
    /**
     * Process Welcu payload.
     *
     * @param Request $request
     */
    public function process(Request $request)
    {
        $typeValues = implode(',', WelcuEventType::getValues());

        $this->validate($request, [
            'action_type' => 'required',
            'sale' => 'array',
            'sale.buyer' => 'array',
            'sale.buyer.first_name' => 'required',
            'sale.buyer.last_name' => 'required',
            'sale.buyer.email' => 'required|email'
        ]);

        $actionType = $request->get('action_type');

        if ($actionType !== WelcuEventType::NewSale) {
            abort(401, 'Event type unsupported.');
        }

        $sale = $request->get('sale');
        $buyer = $sale['buyer'];

        ActiveCampaign::contactSync([
            'email' => $buyer['email'],
            'first_name' => $buyer['first_name'],
            'last_name' => $buyer['last_name'],
        ]);
    }
}
