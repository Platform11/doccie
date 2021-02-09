<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalCodeColumnsToAdministrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('administrations', function (Blueprint $table) {
            $table->string('call_posts_code')->after('code')->nullable();
            $table->string('creditors_code')->after('call_posts_code')->nullable();
            $table->string('debtors_code')->after('creditors_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administrations', function (Blueprint $table) {
            $table->dropColumn(['call_posts_code', 'debtors_code', 'creditors_code']);
        });
    }
}
