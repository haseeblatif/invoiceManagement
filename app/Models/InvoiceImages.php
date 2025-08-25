<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceImages extends Model
{
    protected $table = 'invoice_images';

    protected $fillable = [
        'invoices_id', 'image_type', 'image_path', 'uploaded_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}