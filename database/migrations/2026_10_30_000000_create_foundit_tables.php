<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('user')->index();
            $t->boolean('restricted')->default(false);
            $t->text('restriction_reason')->nullable();
        });

        Schema::create('items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $t->string('type');
            $t->string('title');
            $t->string('category');
            $t->text('description');
            $t->string('location');
            $t->date('date');
            $t->string('image')->nullable();
            $t->string('question');
            $t->timestamps();
        });

        Schema::create('claims', function (Blueprint $t) {
            $t->id();
            $t->foreignId('item_id')->constrained()->cascadeOnDelete();
            $t->foreignId('claimant_id')->constrained('users')->cascadeOnDelete();
            $t->text('answer');
            $t->text('details');
            $t->string('status')->default('pending')->index();
            $t->text('decline_reason')->nullable();
            $t->timestamps();
            $t->unique(['item_id', 'claimant_id']); // one claim per person per item
        });

        Schema::create('meetups', function (Blueprint $t) {
            $t->id();
            $t->foreignId('claim_id')->unique()->constrained()->cascadeOnDelete();
            $t->string('location');
            $t->dateTime('datetime');
            $t->string('note')->nullable();
            $t->string('status')->default('proposed')->index();
            $t->foreignId('proposed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('item_id')->constrained()->cascadeOnDelete();
            $t->foreignId('from_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('to_id')->constrained('users')->cascadeOnDelete();
            $t->text('body');
            $t->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('subject')->nullable();
            $t->text('message');
            $t->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action')->index();
            $t->text('description');
            $t->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        foreach (['activity_logs', 'contact_messages', 'messages', 'meetups', 'claims', 'items'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'restricted', 'restriction_reason']));
    }
};
