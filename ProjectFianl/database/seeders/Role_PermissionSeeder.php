<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Role_PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Creat roles
        $adminRole=Role::query()->create(['name' => 'admin']);
        $teacherRole=Role::query()->create(['name' => 'teacher']);
        $studentRole=Role::query()->create(['name' => 'student']);

        //collecting the permissions
        //these permissions for CRUD except show because show for every one
        $permissions=['delete.student','delete.teacher','add.teacher','show.students','show.teachers',
                        'delete.course','update.course','create.course','show.course',
                        'delete.video','update.video','create.video','show.video',
                        'delete.quiz','update.quiz','create.quiz','show.quiz',
                      ];

        //Creat permissions
        foreach ($permissions as $permission){
            Permission::findOrCreate($permission,'web');
        }

        //assign permission to roles
        $adminRole->syncPermissions($permissions);
        $teacherRole->givePermissionTo(['show.students','delete.course','update.course','create.course','show.course',
            'delete.video','update.video','create.video', 'show.video',
            'delete.quiz','update.quiz','create.quiz','show.quiz']);
        $studentRole->givePermissionTo(['show.course','show.video','show.quiz',]);

        //create admin with role and permissions
        $adminUser=User::factory()->create([
            'full_name' => 'adminName',
            'email' => 'admin@example.com',
            'phone' => '+1.220.337.6304',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'remember_token' => Str::random(10),
            'birthday'=>fake()->date(),
            'address'=>fake()->address(),
            'type'=>'admin',
            'image'=>fake()->text(30),
            'wallet'=>fake()->randomDigit(),
        ]);

        $adminUser->assignRole($adminRole);
        $permissions=$adminRole->permissions()->pluck('name')->toArray();
        $adminUser->givePermissionTo($permissions);
        $adminUser['token'] = $adminUser->createToken("token")->plainTextToken;


        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacherName',
            'email' => 'teacher@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'remember_token' => Str::random(10),
            'birthday'=>fake()->date(),
            'address'=>fake()->address(),
            'type'=>fake()->randomElement(['teacher']),
            'image'=>fake()->text(30),
            'wallet'=>fake()->randomDigit(),
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'studentName',
            'email' => 'student@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'remember_token' => Str::random(10),
            'birthday'=>fake()->date(),
            'address'=>fake()->address(),
            'type'=>fake()->randomElement(['student']),
            'image'=>fake()->text(30),
            'wallet'=>fake()->randomDigit(),
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
    }
}
