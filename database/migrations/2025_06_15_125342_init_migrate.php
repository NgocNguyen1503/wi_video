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
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('video_id')->index();
            $table->string('comment', 255)->nullable();
            $table->integer('user_id')->index();
            $table->timestamps();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->integer('video_id')->index();
            $table->integer('user_id')->index();
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->string('video_url', 255)->nullable();
            $table->string('caption', 255)->nullable();
            $table->integer('author_id')->index();
            $table->timestamps();
        });

        Schema::create('follows', function (Blueprint $table) {
            $table->integer('follow_id')->index();
            $table->integer('user_id')->index();
            $table->timestamps();
        });

        Schema::create('pushes', function (Blueprint $table) {
            $table->string('content', 255)->nullable();
            $table->integer('user_id')->index();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->string('content', 255)->nullable();
            $table->integer('user_id')->index();
            $table->integer('video_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};