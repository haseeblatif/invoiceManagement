<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'vendor_id');
    }

    public function uploadedImages()
    {
        return $this->hasMany(InvoiceImages::class, 'uploaded_by');
    }
}