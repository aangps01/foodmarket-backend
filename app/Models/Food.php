<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'price',
        'rating',
        'types',
        'picturePath',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['picturePath'] = $this->picturePath;

        return $array;
    }

    public function getPicturePathAttribute($value)
    {
        return url('') . Storage::url($value);
    }
}
