<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sessional_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->dateTime('visit_time');
            $table->string('lock_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessional_visits');
    }
};
