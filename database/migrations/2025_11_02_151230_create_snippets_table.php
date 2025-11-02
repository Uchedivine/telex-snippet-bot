<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('snippets', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // Telex user ID
            $table->string('language');
            $table->text('code');
            $table->text('description')->nullable();
            $table->string('channel_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('snippets');
    }
};