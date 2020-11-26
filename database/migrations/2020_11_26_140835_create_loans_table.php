<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(new Organization());
            $table->foreignIdFor(new User());
            $table->enum('status', ['New', 'Renewal']);
            $table->enum('type', [
                'Regular',
                'Emergency',
                'Special Emergency',
                'Provident',
                'Petty Cash',
            ]);
            $table->string('amount');
            $table->string('previous_amount')->nullable();
            $table->string('net_amount')->nullable();
            $table->string('interval')->nullable();
            $table->string('charges');
            $table->text('terms');
            $table->foreignIdFor(new User(), 'comaker_id');
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
        Schema::dropIfExists('loans');
    }
}
