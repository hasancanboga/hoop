<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class AddPermissions extends Migration
{

    private array $permissions = [
        'create_posts',
        'comment_posts',
        'like_posts',
        'edit_profile',
        'follow_users',
        'send_messages',
        'report_users',
        'create_organization',
        'create_challenges',
        'participate_in_challenges',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->permissions as $permission) {
            /** @noinspection PhpUndefinedMethodInspection */
            $permission = Permission::where(['name' => $permission])->first();
            $permission->delete();
        }
    }
}
