<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToReceitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->enum('status', ['ativo', 'inativo'])->default('ativo')->after('tipo_receita');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
