<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponder;
    private $is_customer = false;
    public function is_admin(){
        return auth()->check() && auth()->user()->currentAccessToken()->name === 'adminAuthToken';
    }

    public function numberFormat($val, $decimals = 2 , $decimal_separator = '.' , $thousands_separator = ',' ): string
    {
        return number_format(floatval($val),$decimals,$decimal_separator,$thousands_separator);
    }


}
