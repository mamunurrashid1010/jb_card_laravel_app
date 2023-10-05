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
                ['name' => 'Package', 'parent_id'=>0],
                ['name' => 'Merchant', 'parent_id'=>0],
                ['name' => 'Category', 'parent_id'=>0],
                ['name' => 'Offer', 'parent_id'=>0],
                ['name' => 'Customer', 'parent_id'=>0],
                ['name' => 'Profile', 'parent_id'=>0],
                ['name' => 'Invoice', 'parent_id'=>0],
                //['name' => 'Company', 'parent_id'=>0],

                # Dashboard child
                ['name' => 'Dashboard Manage', 'parent_id'=>1],

                # User Controller
                ['name' => 'Users', 'parent_id'=>2],
                ['name' => 'Role', 'parent_id'=>2],

                # Package child
                ['name' => 'Package Manage', 'parent_id'=>3],

                # Merchant child
                ['name' => 'Merchant Manage', 'parent_id'=>4],

                # Category child
                ['name' => 'Category Manage', 'parent_id'=>5],

                # Offer child
                ['name' => 'Offer Manage', 'parent_id'=>6],

                # Customer child
                ['name' => 'Customer Manage', 'parent_id'=>7],

                # Profile child
                ['name' => 'Profile Manage', 'parent_id'=>8],

                # Invoice child
                ['name' => 'Invoice Manage', 'parent_id'=>9],

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
