<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $guarded = [];
    protected $appends = ['status_label'];
    
    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Draft</span>';
        }
        return '<span class="badge badge-success">Aktif</span>';
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value); 
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
