<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('help_topic_id')->nullable();
            $table->text('title');
            $table->string('email');
            $table->string('status');
            $table->string('priority');
            $table->text('message');
            $table->integer('ticket_number')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
