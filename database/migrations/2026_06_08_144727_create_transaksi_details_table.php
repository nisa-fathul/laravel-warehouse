<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaksi_id');
            $table->foreign('transaksi_id')
                ->references('id')
                ->on('transaksis')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')
                ->references('id')
                ->on('barangs')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->integer('qty');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
