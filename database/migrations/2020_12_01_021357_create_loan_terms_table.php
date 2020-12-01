<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Organization;

class CreateLoanTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_terms', function (Blueprint $table) {
            $table->id();            
            $table->string('organization_id', 50);
            $table->string('plan_name', 50);
            $table->string('loan_type', 50);
            $table->string('interval', 50);
            $table->string('interest_in_percent', 50);
            $table->string('amount', 50);
            $table->string('total_gain', 50);
            $table->string('penalty_per_interval', 50);            
            $table->string('description', 50);           
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
        Schema::dropIfExists('loan_terms');
    }
}
