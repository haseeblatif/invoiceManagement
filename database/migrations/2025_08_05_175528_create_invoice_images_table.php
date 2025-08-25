<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::create('invoice_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoices_id')->constrained()->onDelete('cascade');
            $table->enum('image_type', ['Invoice', 'Payment_Proof']);
            $table->string('image_path');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_images');
    }
}
