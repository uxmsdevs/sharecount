<?php namespace Uxms\Sharecount\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class CreateSharecountMappings extends Migration
{
    public function up()
    {
        Schema::create('uxms_sharecount_webpages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->index();
            $table->text('url');
            $table->integer('count_face')->default(0);
            $table->integer('count_twit')->default(0);
            $table->integer('count_gp')->default(0);
            $table->timestamp('last_fetched')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uxms_sharecount_webpages');
    }

}
