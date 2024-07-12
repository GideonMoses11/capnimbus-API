<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\BaseModel;
use App\Models\Kyc;
use App\Models\Cart;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Account;
use App\Models\Deposit;
use App\Models\Profile;
use App\Models\WishList;
use App\Models\Investment;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends BaseModel implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'id',
        'username',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'gender',
        'country',
        'address',
        'first_name',
        'last_name',
        'ref_code',
        'upline_code',
        'referrer_id',
    ];

    protected $with = ['account'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }

    public function nameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function account(){
        return $this->hasOne(Account::class, 'user_id');
    }

    public function kyc(){
        return $this->hasOne(Kyc::class, 'user_id');
    }

    public function referrals(){
        return $this->hasMany(User::class, 'referrer_id');
    }

    public function referrer(){
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function upline(){
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function investments(){
        return $this->hasMany(Investment::class);
    }

    public function deposits(){
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals(){
        return $this->hasMany(Withdrawal::class);
    }

}
