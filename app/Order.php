<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $appends = ['status_label'];
    
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Menunggu Pembayaran</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge badge-primary">Menunggu Konfirmasi</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge badge-info">Proses</span>';
        } elseif ($this->status == 3) {
            return '<span class="badge badge-info">Dikirim</span>';
        } elseif ($this->status == 4) {
            return '<span class="badge badge-success">Selesai</span>';
        } elseif ($this->status == 5) {
            return '<span class="badge badge-warning">Pending</span>';
        } elseif ($this->status == 6) {
            return '<span class="badge badge-dark">Dibatalkan</span>';
        }
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function return()
    {
        return $this->hasOne(OrderReturn::class);
    }
}
