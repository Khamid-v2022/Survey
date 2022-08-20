<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('question');
            $table->string('question_option'); // ['A', 'B', 'C'];
            $table->enum('is_require', ['Yes', 'No'])->default('Yes');
            $table->enum('answer_type', ['Text', 'Option', 'Rating', 'Paragraph'])->default('Text');  
            $table->foreign('form_id')
                ->references('id')
                ->on('webforms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
