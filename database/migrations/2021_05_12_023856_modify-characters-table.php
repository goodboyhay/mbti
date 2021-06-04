<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('name')->change();
            $table->dropColumn('description');
            $table->longText('overview')->after('name');
            $table->text('advantages')->after('overview');
            $table->text('weakness')->after('advantages');
            $table->text('suitable_jobs')->after('weakness');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropIfExists('description');
        });
    }
}
