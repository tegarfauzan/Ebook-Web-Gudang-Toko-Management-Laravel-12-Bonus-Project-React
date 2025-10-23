<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Warehouse extends Model
{
    use SoftDeletes;

    // Mass Assignment: Kolom yang diizinkan
    protected $fillable = [
        'name',
        'address',
        'photo',
        'phone',
    ];

    // Relasi: Many-to-Many dengan Product (Stok Fisik)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    // Accessor: Mengubah path foto menjadi URL publik
    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return url(Storage::url($value));
    }
}
