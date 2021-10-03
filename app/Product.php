<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = [];
    protected $appends = ['status_label'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Draft</span>';
        } else {
            return '<span class="badge badge-success">Aktif</span>';
        }
    }

    public function setSlugAttribute($value) {
        if (static::whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }    
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug) {
        $original = $slug;
        $count = 2;
        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }
        return $slug;
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variant()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('price', 'ASC');;
    }

}
