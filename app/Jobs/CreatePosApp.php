<?php
namespace App\Jobs;

use App\Helpers\Pos\PosIntegration;
use App\Helpers\StringHelper;
use App\Models\UserApps ;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreatePosApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    const DB_PREFIX       = 'pos';
    const CONFIG_LOCATION = '/app/config/';
    public $userApp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userApp)
    {
        $this->userApp = $userApp;
        // $this->userApp = $app;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->runScripts();
        //$this->copyConfigFiles();
        //$this->createUserPassword();
        //$this->loadDemoContents();
    }

    private function runScripts()
    {
        try {
            $dbName = self::DB_PREFIX . '_' . $this->userApp->id . '_' . $this->userApp->sub_domain;
            DB::statement('CREATE DATABASE ' . $dbName);
            DB::statement('USE ' . $dbName);
            DB::unprepared(file_get_contents('/Users/muhsinzyne/public_html/slasah/pos_v2/install/saas-database/pos_demo.sql'));
            $config  = file_get_contents('https://raw.githubusercontent.com/muhsinzyne/raw_files/main/pos/db_config.txt');
            DB::statement('USE ' . config('database.connections.mysql.database'));
            $posAppUrl = config('app.saas_protocol') . $this->userApp->sub_domain . '.' . config('app.saas');
            $config    = StringHelper::replaceWordsFromTemplate($config, [
                'baseUrl'      => $posAppUrl,
                'hostName'     => config('database.connections.mysql.host'),
                'userName'     => config('database.connections.mysql.username'),
                'password'     => config('database.connections.mysql.password'),
                'databaseName' => $dbName,
            ]);

            file_put_contents(
                config('app.saas_pos_location') .
                self::CONFIG_LOCATION .
                $this->userApp->sub_domain .
                '.' . config('app.saas') .
                '-config.php',
                $config
            );

            $this->userApp->db_name = $dbName;
            $this->userApp->update();
            $user = $this->userApp->user;

            $nameSplit = explode(' ', $user->name);
            $fistName  = $nameSplit[0];
            $lastName  = '';
            if (count($nameSplit) > 1) {
                $lastName = $nameSplit[1];
            }

            $requestData = [
                'username'       => $user->username,
                'email'          => $user->email,
                'status'         => 1,
                'password'       => $this->userApp->app_password,
                'first_name'     => $fistName,
                'last_name'      => $lastName,
                'company'        => $this->userApp->store_name,
                'phone'          => $user->mobile_no,
                'gender'         => '',
                'group'          => 1,
                'biller_id'      => 0,
                'warehouse_id'   => 0,
                'view_right'     => 0,
                'edit_right'     => 0,
                'allow_discount' => 0,
                'notify'         => 0,
                'store_name'     => $this->userApp->store_name
            ];

            $posIntegration = new PosIntegration($posAppUrl);
            $posIntegration->migrateAccountInformations($requestData);
            $this->userApp->app_password = '';
            $this->userApp->app_build_at = Carbon::now();
            $this->userApp->update();
        } catch (Exception $e) {
        }

        // $myfile = fopen(__DIR__ . 'muhsin-demo.php', 'w') or die('Unable to open file!');
        // $txt    = "Mickey Mouse\n";
        // fwrite($myfile, $txt);
        // $txt = "Minnie Mouse\n";
    }

    private function copyConfigFiles()
    {
    }

    private function createUserPassword()
    {
    }

    private function loadDemoContents()
    {
    }

    private function findUserInfor()
    {
    }
}
