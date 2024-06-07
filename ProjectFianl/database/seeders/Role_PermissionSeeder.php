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
            'password' => bcrypt('88888888'), // password
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
            'full_name' => 'teacher1',
            'email' => 'teacher1@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher2',
            'email' => 'teacher2@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher3',
            'email' => 'teacher3@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher4',
            'email' => 'teacher4@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher5',
            'email' => 'teacher5@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher6',
            'email' => 'teacher6@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher7',
            'email' => 'teacher7@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher8',
            'email' => 'teacher8@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher9',
            'email' => 'teacher9@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create teacher with role and permissions
        $teacherUser=User::factory()->create([
            'full_name' => 'teacher10',
            'email' => 'teacher10@example.com',
            'phone' => '+1.220.337.6302',
            'email_verified_at' => now(),
            'password' => bcrypt('66666666'), // password
            'type' => 'teacher',
            'wallet' => 2000,
        ]);
        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        $teacherUser->assignRole($teacherRole);
        $permissions=$teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);
        $teacherUser['token'] = $teacherUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student1',
            'email' => 'student1@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 500,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student2',
            'email' => 'student2@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;


        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student3',
            'email' => 'student3@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 250,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;


        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student4',
            'email' => 'student4@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student5',
            'email' => 'student5@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student6',
            'email' => 'student6@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 600,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;


        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student7',
            'email' => 'student7@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student8',
            'email' => 'student8@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student9',
            'email' => 'student9@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;

        //create student with role and permissions
        $studentUser=User::factory()->create([
            'full_name' => 'student10',
            'email' => 'student10@example.com',
            'phone' => '+1.220.337.6303',
            'email_verified_at' => now(),
            'password' => bcrypt('55555555'), // password
            'type' => 'student',
            'wallet' => 200,
        ]);
        $studentUser->assignRole($studentRole);
        $permissions=$studentRole->permissions()->pluck('name')->toArray();
        $studentUser->givePermissionTo($permissions);
        $studentUser['token'] = $studentUser->createToken("token")->plainTextToken;
    }
}
