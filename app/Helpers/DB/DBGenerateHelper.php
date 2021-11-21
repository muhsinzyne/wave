<?php
namespace App\Helpers\DB;

use App\Models\Enum\DriverConst;
use App\Models\Enum\GeneralConst;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PDO;

class DBGenerateHelper
{
    /**
     * Class constructor.
     */
    public $driver;
    public $cpanelPassowrd;
    public $cpanelUserName;
    public $dns;

    public function __construct()
    {
        if (in_array(config('app.env'), GeneralConst::CPANELENV)) {
            $this->driver = DriverConst::API;
        } else {
            $this->driver = DriverConst::LOCAL;
        }
        $this->cpanelPassowrd = config('app.cpassword');
        $this->cpanelUserName = config('app.cuser');
        $this->dns            = config('app.cdns');
    }

    public function importTables($dbName)
    {
        DB::purge('mysql');
        Config::set('database.connections.mysql.database', $dbName);
        DB::reconnect('mysql');
        DB::unprepared(file_get_contents(config('app.saas_pos_location') . '/install/saas-database/pos_demo.sql'));
        DB::purge('mysql');
        Config::set('database.connections.mysql.database', config('database.connections.mysql.database'));
        DB::reconnect('mysql');
    }

    public function createDataBase($dbName)
    {
        if ($this->driver == DriverConst::API) {
            // cpanel create database script gose here
            $query = 'https://' . $this->dns . ':2083/json-api/cpanel?cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=adddb&cpanel_jsonapi_apiversion=1&arg-0=' . $dbName;

            $curl = curl_init();                                // Create Curl Object
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);       // Allow self-signed certs
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);       // Allow certs that do not match the hostname
            curl_setopt($curl, CURLOPT_HEADER, 0);               // Do not include header in output
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);       // Return contents of transfer on curl_exec
            $header[0] = 'Authorization: Basic ' . base64_encode($this->cpanelUserName . ':' . $this->cpanelPassowrd) . "\n\r";
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);    // set the username and password
            curl_setopt($curl, CURLOPT_URL, $query);            // execute the query
            $result = curl_exec($curl);
            if ($result == false) {
                echo '<pre>';
                print_r($curl);
                echo '</pre>';
                echo '<pre>';
                print_r($header);
                echo '</pre>';
                //die();
                //die();
                echo '<pre>';
                print_r($query);
                echo '</pre>';
                die();

                // log error if curl exec fails
            }
            curl_close($curl);

            return true;
        } else {
            return DB::statement('CREATE DATABASE ' . $dbName);
        }
    }
}
