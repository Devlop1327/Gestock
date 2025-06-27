<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero agregamos la columna como nullable
        Schema::table('movimientos', function (Blueprint $table) {
            $table->foreignId('user_id')->after('producto_id')->nullable();
        });

        // Obtenemos el primer usuario del sistema
        $defaultUser = User::first();

        if ($defaultUser) {
            DB::table('movimientos')->whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        }

        // Eliminamos la columna y la volvemos a crear como no nullable
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('movimientos', function (Blueprint $table) {
            $table->foreignId('user_id')->after('producto_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}; 