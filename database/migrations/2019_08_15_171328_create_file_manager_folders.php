<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileManagerFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_manager_folders', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->integer('unique_id');
            $table->integer('parent_id')->default(0);

            $table->text('name')->nullable();
            $table->text('type')->nullable();
            $table->string('user_scope')->default('master');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_manager_folders');
    }
}
