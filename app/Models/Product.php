<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    // Mass Assignment: Kolom yang diizinkan
    protected $fillable = [
        'name',
        'thumbnail',
        'about',
        'price',
        'category_id',
        'is_popular',
    ];

    // Relasi 1: belongsTo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi 2: Many-to-Many dengan Merchant (Stok Kepemilikan)
    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    // Relasi 3: Many-to-Many dengan Warehouse (Stok Fisik)
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    // Relasi 4: hasMany TransactionProducts (Item yang terjual)
    public function transactions()
    {
        return $this->hasMany(TransactionProduct::class);
    }

    // Helper: Hitung total stok di semua Warehouse
    public function getWarehouseProductStock()
    {
        return $this->warehouses()->sum('stock');
    }

    // Helper: Hitung total stok kepemilikan Merchant
    public function getMerchantProductStock()
    {
        return $this->merchants()->sum('stock');
    }

    // Accessor: Mengubah path thumbnail menjadi URL publik
    public function getThumbnailAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return url(Storage::url($value));
    }
}
