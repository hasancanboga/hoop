<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class AddRoles extends Migration
{
    private array $roles = [
        // user roles:
        "super_admin",
        "admin",
        "intermediate_admin",
        "basketball_player",
        "personal_trainer",
        "coach",
        "manager",
        "conditioner",
        "referee",
        "sports_doctor",
        "physiotherapist",
        "fitness_masseur",
        "psychologist",
        "nutritionist",
        "basketball_fan",

        // organization roles:
        "org_brand",
        "org_foundation",
        "org_educational_institution",
        "org_club",
        "org_basketball_school",
        "org_public_institution",
        "org_media_organization",
        "org_sports_field",
    ];

    /**
     * Run the migrations.
     *antrenörm
     * @return void
     */
    public function up()
    {
        foreach ($this->roles as $role) {
            Role::create(['name' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->roles as $role) {
            /** @noinspection PhpUndefinedMethodInspection */
            $role = Role::where(['name' => $role])->first();
            $role->delete();
        }
    }
}
