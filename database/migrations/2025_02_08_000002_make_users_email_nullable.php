<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->upSqlite();
        } else {
            Schema::table('users', function (Blueprint $table) {
                // В MySQL индекс users_email_unique уже существует с предыдущих миграций,
                // поэтому меняем только nullable, не создавая уникальный индекс повторно.
                $table->string('email')->nullable()->change();
            });
        }
    }

    protected function upSqlite(): void
    {
        $cols = Schema::getColumnListing('users');

        DB::statement('PRAGMA foreign_keys=off');

        Schema::create('users_new', function (Blueprint $table) use ($cols) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            if (in_array('phone', $cols, true)) {
                $table->string('phone', 20)->nullable()->unique();
            }
            if (in_array('role', $cols, true)) {
                $table->string('role', 20)->default('patient');
            }
            if (in_array('is_blocked', $cols, true)) {
                $table->boolean('is_blocked')->default(false);
            }
        });

        $colList = implode(', ', $cols);
        DB::statement("INSERT INTO users_new ({$colList}) SELECT {$colList} FROM users");

        Schema::drop('users');
        Schema::rename('users_new', 'users');

        DB::statement('PRAGMA foreign_keys=on');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->downSqlite();
        } else {
            Schema::table('users', function (Blueprint $table) {
                // Обратно делаем поле обязательным, не трогая уже существующий уникальный индекс.
                $table->string('email')->nullable(false)->change();
            });
        }
    }

    protected function downSqlite(): void
    {
        $cols = Schema::getColumnListing('users');
        $colList = implode(', ', $cols);

        DB::statement('PRAGMA foreign_keys=off');

        Schema::create('users_old', function (Blueprint $table) use ($cols) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            if (in_array('phone', $cols, true)) {
                $table->string('phone', 20)->nullable()->unique();
            }
            if (in_array('role', $cols, true)) {
                $table->string('role', 20)->default('patient');
            }
            if (in_array('is_blocked', $cols, true)) {
                $table->boolean('is_blocked')->default(false);
            }
        });

        $emailExpr = "COALESCE(email, '')";
        $selectCols = array_map(fn ($c) => $c === 'email' ? $emailExpr : $c, $cols);
        $selectList = implode(', ', $selectCols);
        DB::statement("INSERT INTO users_old ({$colList}) SELECT {$selectList} FROM users");

        Schema::drop('users');
        Schema::rename('users_old', 'users');

        DB::statement('PRAGMA foreign_keys=on');
    }
};
