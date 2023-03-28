<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductsColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('description');
            $table->integer('sale_price')->nullable()->after('price');
            $table->boolean('hidden')->default(false)->after('sale_price');
            $table->boolean('featured')->default(false)->after('hidden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->dropColumn('sale_price');
            $table->dropColumn('hidden');
            $table->dropColumn('featured');
        });
    }
}
