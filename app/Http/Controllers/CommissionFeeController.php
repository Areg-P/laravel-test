<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExchangeRatesService;

class CommissionFeeController extends Controller
{
    public function CalculateCommissionFee(
        Request $request,
        ExchangeRatesService $ExchangeRatesService
    ) {
        if (!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE) {
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
				if (!$header)
					$header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
		dd($data);
        return $ExchangeRatesService->Exchange(30000, 'EUR');
    }
}
