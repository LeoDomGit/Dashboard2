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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('slug',255);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->timestamps();
        });
        Schema::create('posts2', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255);
            $table->string('summary',255)->nullable();
            $table->string('images',255);
            $table->longText('content');
            $table->boolean('crawl')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('id_topic')->nullable();
            $table->timestamps();
            $table->foreign('id_topic')->references('id')->on('topics');
        });

        Schema::create('code', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255);
            $table->longText('content');
            $table->unsignedBigInteger('id_posts');
            $table->timestamps();
            $table->foreign('id_posts')->references('id')->on('posts2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
