<?php
namespace App\Helpers\Pos;

use Illuminate\Support\Facades\Http;

class PosIntegration
{
    /**
     * Class constructor.
     */
    public $instance;
    public $API = '';

    public function __construct($api)
    {
        $this->API = $api;
    }

    public function migrateAccountInformations($params)
    {
        $endPoint = $this->API . '/admin/setup';
        $headers  = [
            // 'PublicKey' => $this->instance->public_key,
            // 'SecretKey' => $this->instance->api_token,
        ];
        $response = Http::post($endPoint, $params);
        if ($response->successful()) {
            $body = $response->body();

            return $body;
        } else {
            return false;
            // if some error
            // throw that error
        }
    }
}
