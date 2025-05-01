<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PreOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users() {
        return $this->belongsTo(User::class, 'id_users');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
