<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('productos') && !Schema::hasColumn('productos', 'created_at')) {
            Schema::table('productos', function ($table) {
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
