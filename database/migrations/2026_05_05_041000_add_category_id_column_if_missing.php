<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('contacts', 'category_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('company');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('contacts', 'category_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
        }
    }
};
