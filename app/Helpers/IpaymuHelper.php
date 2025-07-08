<?php

use App\Models\Ipaymu;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Pastikan fungsi tidak dideklarasikan ulang.
 */
function GenerateSignature($body, $method)
{
    // Ambil konfigurasi langsung menggunakan helper config()

    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . config('ipaymu.va') . ':' . $requestBody . ':' . config('ipaymu.api_key');
    $signature    = hash_hmac('sha256', $stringToSign, config('ipaymu.api_key'));
    return $signature;
}

function listPaymentIpaymu()
{
    $body['account'] = config('ipaymu.va');
    $signature       = GenerateSignature($body, 'GET');
    $response        = Http::withHeaders([
        'Content-Type' => 'application/json',
        'signature'    => $signature,
        'va'           => config('ipaymu.va'),
        'timestamp'    => Date('YmdHis'),
    ])->get(config('ipaymu.url_channels'), [
        'account' => config('ipaymu.va'),
    ]);
    if ($response->json()['Status'] == 200 && $response->json()['Success'] == true) {
        $paymentGateways = $response->json()['Data'];
        foreach ($paymentGateways as $paymentGateway) {
            if ($paymentGateway['Code'] == 'va' || $paymentGateway['Code'] == 'cstore' || $paymentGateway['Code'] == 'qris') {
                $TsPgIpaymu = Ipaymu::where('code', $paymentGateway['Code'])->first();
                if ($TsPgIpaymu == null) {
                    $TsPgIpaymu = Ipaymu::create([
                        'code'        => $paymentGateway['Code'],
                        'name'        => $paymentGateway['Name'],
                        'description' => $paymentGateway['Description'],
                        'channels'    => json_encode($paymentGateway['Channels']),
                    ]);
                } else {
                    $TsPgIpaymu->update([
                        'code'        => $paymentGateway['Code'],
                        'name'        => $paymentGateway['Name'],
                        'description' => $paymentGateway['Description'],
                        'channels'    => json_encode($paymentGateway['Channels']),
                    ]);
                }
            }
        }
    }
} // api get list payment method

function directPaymentIpaymu($body)
{
    $signature = GenerateSignature($body, 'POST');
    $response  = Http::withHeaders([
        'Content-Type' => 'application/json',
        'signature'    => $signature,
        'va'           => config('ipaymu.va'),
        'timestamp'    => Date('YmdHis'),
    ])->post(config('ipaymu.url_payment'), $body);

    $responseData = $response->json();
    if ($response->failed() || $responseData['Status'] != 200 || ! $responseData['Success']) {
        Log::error('Ipaymu payment error: ', $responseData);
        return null;
    }

    Log::info('Ipaymu payment created successfully: ', $responseData);
    return $responseData['Data'];
}

function randomCode()
{
    $randomPart1 = bin2hex(random_bytes(4));
    $randomPart2 = bin2hex(random_bytes(2));
    $randomPart3 = bin2hex(random_bytes(2));
    $randomPart4 = bin2hex(random_bytes(2));
    $randomPart5 = bin2hex(random_bytes(6));
    $randomCode  = $randomPart1 . '-' . $randomPart2 . '-' . $randomPart3 . '-' . $randomPart4 . '-' . $randomPart5;
    return $randomCode;
}
