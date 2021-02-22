<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('call_posts_code')->nullable();
            $table->string('creditors_code')->nullable();
            $table->string('debtors_code')->nullable();
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_email');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('relation_manager_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('relation_manager_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrations');
    }
}
