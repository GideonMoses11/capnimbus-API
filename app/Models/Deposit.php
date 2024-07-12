<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
                            'reference',
                            'amount_usd',
                            'status',
                            'user_id'
                        ];

    protected $with = ['user'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->latest();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
