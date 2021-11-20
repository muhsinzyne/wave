<?php
namespace App\Models;

use App\Models\Enum\GeneralConst;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Null_;

class UserApps extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TRIAL_MODE    = 'Trial Mode';
    const ACTIVE        = 'Active';
    const TRIAL_EXPIRED = 'Trial Expired';
    const INACTIVE      = 'Inactive';
    const APP_BUILDING  = 'App Building';
    protected $table    = 'user_apps';

    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_name',
        'sub_domain',
        'is_trial',
        'trial_end_at',
        'subscription_ends_at',
        'app_password',
        'app_build_at',
        'status',
        'db_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reservedProducts()
    {
        return $this->hasMany('App\Models\AppUserOrderRequestProduct', 'order_request_id', 'order_request_id');
    }

    public function subscriptionEndsAt()
    {
        return Carbon::createFromDate($this->subscription_ends_at)->format(GeneralConst::CREATED_AT_FORMAT);
    }

    public function appStatus()
    {
        $now = Carbon::now();
        if ($this->app_build_at == null) {
            return self::APP_BUILDING;
        }
        if ($this->is_trial && $this->trial_end_at >= $now) {
            return self::TRIAL_MODE;
        } elseif ($this->is_trial && $this->trial_end_at < $now) {
            return self::TRIAL_EXPIRED;
        } elseif (!$this->is_trial && $this->subscription_ends_at >= $now && $this->status == GeneralConst::TRUE) {
            return self::ACTIVE;
        } elseif ($this->status == GeneralConst::FALSE) {
            return self::INACTIVE;
        } else {
            return self::INACTIVE;
        }
    }

    public static function getUserApps($userId)
    {
        return self::where('user_id', $userId)->get();
    }
}
