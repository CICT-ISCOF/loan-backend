<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Organization;

class CreateSavingTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_terms', function (Blueprint $table) {
            $table->id();            
            $table->string('organization_id', 50);
            $table->string('interests_per_year', 445)->nullable();
            $table->string('charges_per_transaction', 445)->nullable();
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
        Schema::dropIfExists('saving_terms');
    }
}
