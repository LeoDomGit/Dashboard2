<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255);
            $table->string('summary',255);
            $table->string('image',255);
            $table->unsignedBigInteger('id_collection');
            $table->longText('content');
            $table->boolean('status')->default(0);
            $table->boolean('highlight')->default(0);
            $table->timestamps();
            $table->foreign('id_collection')->references('id')->on('post_collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
