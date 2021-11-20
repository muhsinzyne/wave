<?php
namespace App\Helpers\DB;

use App\Models\Enum\DriverConst;
use App\Models\Enum\GeneralConst;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DBGenerateHelper
{
    /**
     * Class constructor.
     */
    public $driver;

    public function __construct()
    {
        if (in_array(config('app.env'), GeneralConst::CPANELENV)) {
            $this->driver = DriverConst::API;
        } else {
            $this->driver = DriverConst::LOCAL;
        }
    }

    public function createDataBase($dbName)
    {
        if ($this->driver == DriverConst::API) {
            // cpanel create database script gose here
        } else {
            DB::statement('CREATE DATABASE ' . $dbName);
        }
    }
}
