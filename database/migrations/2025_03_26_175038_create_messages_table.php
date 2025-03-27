<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Message belongs to a User
            $table->unsignedBigInteger('category_id'); // Explicitly define unsignedBigInteger
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); // Ensure correct reference
            $table->unsignedBigInteger('parent_id')->nullable(); // Explicitly define unsignedBigInteger for self-referencing
            $table->foreign('parent_id')->references('id')->on('messages')->onDelete('cascade'); // Self-referencing foreign key
            $table->string('name');
            $table->string('iconpath');
            $table->text('chat');
            $table->string('timestamp');
            $table->boolean('hasUnread')->default(false);
            $table->boolean('isMe')->default(false);
            $table->timestamps();

            $table->engine = 'InnoDB'; // Ensure InnoDB for foreign keys
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
