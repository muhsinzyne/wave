<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApps extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_apps';

    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'store_name', 'sub_domain', 'is_trial', 'trial_end_at', 'subscription_ends_at', 'app_password'
    ];
}
