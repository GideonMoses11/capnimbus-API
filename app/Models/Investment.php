<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id',
                            'amount_usd',
                            'roi',
                            'expiry_date',
                            'status',
                            'user_id',
                            'plan_id'
                        ];

    protected $with = ['user'];

    protected $dates = ['expiry_date'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
