<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('parent_id')->default(0)->nullable();
            $table->timestamps();
        });

        DB::table('permissions')->insert(
            [
                ['name' => 'Dashboard', 'parent_id'=>0],
                ['name' => 'User Controller', 'parent_id'=>0],
                //['name' => 'Products', 'parent_id'=>0],
                //['name' => 'Company', 'parent_id'=>0],

                # Dashboard child
                ['name' => 'Dashboard Manage', 'parent_id'=>1],

                # User Controller
                ['name' => 'Users', 'parent_id'=>2],
                ['name' => 'Role', 'parent_id'=>2],

                # Products
                //['name' => 'Products', 'parent_id'=>3],

                # Company child
                //['name' => 'Company Manage', 'parent_id'=>3],


            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
