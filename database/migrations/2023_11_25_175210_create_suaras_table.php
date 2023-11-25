<?php

// database/migrations/{timestamp}_create_suaras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('votable_id');
            $table->string('votable_type');
            // Tambahkan kolom kategori jika perlu
            $table->string('kategori');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            // Tambahkan relasi untuk model votable (Guru)
            $table->index(['votable_id', 'votable_type']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suaras');
    }
}

