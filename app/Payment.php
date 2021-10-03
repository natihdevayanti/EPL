<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    protected $appends = ['status_label'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Menunggu Konfirmasi</span>';
        } else if ($this->status == 1) {
            return '<span class="badge badge-success">Diterima</span>';
        } else if ($this->status == 2) {
            return '<span class="badge badge-dark">Dibatalkan</span>';
        }
    }
}
