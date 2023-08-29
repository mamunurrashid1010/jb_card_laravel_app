<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active');
            //$table->text('Access')->nullable(true);
            $table->rememberToken();
            $table->timestamps();
        });

        # create default admin
        User::query()->create([
            'name'      => 'Admin',
            //'image'   => '',
            'email'     => 'admin@gmail.com',
            'type'      => 'Admin',
            'password'  => Hash::make(12345),
            'created_at' => Carbon::now()
        ]);

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
