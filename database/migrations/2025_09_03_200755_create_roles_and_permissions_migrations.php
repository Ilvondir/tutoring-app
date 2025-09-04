<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'student']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::destroy(['name' => 'teacher']);
        Role::destroy(['name' => 'student']);
    }
};
