<?php

// database/migrations/xxxx_xx_xx_create_activation_n_reset_tokens_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivationNResetTokensTable extends Migration
{
    public function up()
    {
        Schema::create('activation_n_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('token');
            $table->string('type');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activation_n_reset_tokens');
    }
}
