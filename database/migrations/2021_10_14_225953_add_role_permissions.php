<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            $role->givePermissionTo('create_posts');
            $role->givePermissionTo('comment_posts');
            $role->givePermissionTo('like_posts');
            $role->givePermissionTo('edit_profile');
            $role->givePermissionTo('follow_users');
            $role->givePermissionTo('send_messages');
            $role->givePermissionTo('report_users');

            if (!Str::startsWith($role->name, "org_")) {
                $role->givePermissionTo('create_organization');
            }

            $challengeCreators = [
                "personal_trainer",
                "coach",
                "manager",
                "conditioner",
                "org_club",
                "org_basketball_school"
            ];

            if (in_array($role->name, $challengeCreators)) {
                $role->givePermissionTo('create_challenges');
            }

            if ($role->name == 'basketball_player') {
                $role->givePermissionTo('participate_in_challenges');
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::first();
        $role->permissions()->truncate();
    }
}
