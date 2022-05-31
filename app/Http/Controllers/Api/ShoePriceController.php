<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShoePriceController extends Controller
{
    public function getShoePrice(Request $request)
    {
        $skus = array();
        foreach ($request->query('sku') as $sku)
        {
            if($sku === '501')
            {
                return response()->json(['error' => 'Internal Server Error'], 501);
            }

            if($sku === '98765')
            {
                sleep(7);
            }

            $skus[] = $sku;
        }

        $priceData = array();
        foreach ($skus as $sku)
        {
            if(!Cache::get($sku))
            {
                Cache::put($sku, rand(300, 1200));
            }

            $priceExcVat = Cache::get($sku);
            $priceIncVat = $priceExcVat * 1.25;

            $shoeData = (object) array(
                'sku' => $sku,
                'vat' => 25,
                'priceExcVat' => $priceExcVat,
                'priceIncVat' => $priceIncVat,
                'priceExcVatFormatted' => 'SEK' . number_format(
                    $priceExcVat, 2, '.', ''),
                'priceIncVatFormatted' => 'SEK' . number_format(
                        $priceIncVat, 2, '.', ''));

            array_push($priceData, $shoeData);
        }

        return $priceData;
    }
}
