<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create categories table with user_id
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Adding the user_id column with foreign key
            $table->timestamps();
        });

        // Add category_id to todos table
        Schema::table('todos', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        // Drop category_id from todos table
        Schema::table('todos', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Drop categories table
        Schema::dropIfExists('categories');
    }
};
