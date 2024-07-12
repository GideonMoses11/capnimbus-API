<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
                            'name',
                            'min_price',
                            'max_price',
                            'roi',
                            'frequency'
                        ];


    public function investments(){
        return $this->hasMany(Investment::class);
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
