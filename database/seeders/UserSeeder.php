<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\RolesAndPermissions\{Role,Permission};
use App\Models\User;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        //truncate table
        DB::statement("SET foreign_key_checks=0");
            DB::table('role_user')->truncate();
            DB::table('permission_role')->truncate();
            Permission::truncate();
            Role::truncate();
        DB::statement("SET foreign_key_checks=1");

        /*UserAdmin*/
        $userAdmin = User::where('email','admin@cantv.com.ve')->first();

        if($userAdmin){$userAdmin->delete();}

        $userAdmin = User::create([
	        'name' => 'admin',
	        'email' => 'admin@cantv.com.ve',
            'email_verified_at' => now(),
	        'password' => Hash::make('123456')
        ]);


        /*UserRoot*/
        $userRoot = User::where('email','root@cantv.com.ve')->first();

        if($userRoot){$userRoot->delete();}

        $userRoot = User::create([
	        'name' => 'root',
	        'email' => 'root@cantv.com.ve',
            'email_verified_at' => now(),
	        'password' => Hash::make('123456')
        ]);

        /////////////////////////////
        ///////      Rol     ///////
        ///////////////////////////
        ////////    Root   ///////
        /////////////////////////
        $rol_root = Role::create([
            'slug' => 'root',
            'name' => 'Root',
            'description' => 'Super usuario',
            'full-access' => 'yes'
        ]);
        
        /////////////////////////////
        ///////     Rol     ////////
        ///////////////////////////
        ////////   Admin   ///////
        /////////////////////////
        $rol_admin = Role::create([
            'slug' => 'admin',
            'name' => 'Administrador',
            'description' => 'Administrador',
            'full-access' => null
        ]);
        
        /////////////////////////////
        ///////      Rol     ///////
        ///////////////////////////
        ////////    User   ///////
        /////////////////////////
        $rol_user = Role::create([
            'slug' => 'user',
            'name' => 'Usuarios',
            'description' => 'Usuario',
            'full-access' => null
        ]);

        /////////////////////////////
        ///////      Rol     ///////
        ///////////////////////////
        ////////    Role   ///////
        /////////////////////////
        $rol_role = Role::create([
            'slug' => 'rol',
            'name' => 'Roles',
            'description' => 'Roles',
            'full-access' => null
        ]);

        /////////////////////////////
        ///////      Rol     ///////
        ///////////////////////////
        ////////  Handbook ///////
        /////////////////////////
        $rol_handbook = Role::create([
            'slug' => 'handbook',
            'name' => 'Manuales',
            'description' => 'Manuales',
            'full-access' => null
        ]);     

        /////////////////////////////
        ///////  Permissions ///////
        ///////////////////////////
        ////////    User   ///////
        /////////////////////////
        $permission = Permission::create([//1
            'slug' => 'user.index',
            'name' => 'Navegar usuarios',
            'description' => 'Lista y navega todos los usuarios del sistema'
        ]);
        $permission = Permission::create([//2
            'slug' => 'user.show',
            'name' => 'Ver detalle de usuario',
            'description' => 'Ver en detalle cada usuario del sistema'
        ]);
        $permission = Permission::create([//3
            'slug' => 'user.create',
            'name' => 'Creación de usuarios',
            'description' => 'Podría crear nuevos usuarios en el sistema'
        ]);
        $permission = Permission::create([//4
            'slug' => 'user.edit',
            'name' => 'Edición de usuarios',
            'description' => 'Podría editar cualquier dato de un usuario del sistema'
        ]);
        $permission = Permission::create([//5
            'slug' => 'user.destroy',
            'name' => 'Eliminar usuario',
            'description' => 'Podría eliminar cualquier usuario del sistema'
        ]);

        /////////////////////////////
        ///////  Permissions ///////
        ///////////////////////////
        ////////    Role   ///////
        /////////////////////////
        $permission = Permission::create([//6
            'slug' => 'role.index',
            'name' => 'Navegar roles',
            'description' => 'Lista y navega todos los roles del sistema'
        ]);
        $permission = Permission::create([//7
            'slug' => 'role.show',
            'name' => 'Ver detalle de un rol',
            'description' => 'Ver en detalle cada rol del sistema'
        ]);
        $permission = Permission::create([//8
            'slug' => 'role.create',
            'name' => 'Creación de roles',
            'description' => 'Podría crear nuevos roles en el sistema'
        ]);
        $permission = Permission::create([//9
            'slug' => 'role.edit',
            'name' => 'Edición de roles',
            'description' => 'Podría editar cualquier dato de un rol del sistema'
        ]);
        $permission = Permission::create([//10
            'slug' => 'role.destroy',
            'name' => 'Eliminar roles',
            'description' => 'Podría eliminar cualquier rol del sistema'
        ]);

        /////////////////////////////
        ///////  Permissions ///////
        ///////////////////////////
        ////////  Handbook ///////
        /////////////////////////
        $permission = Permission::create([//11
            'slug' => 'handbook.index',
            'name' => 'Navegar manuales',
            'description' => 'Lista y navega todos los manuales del sistema'
        ]);
        $permission = Permission::create([//12
            'slug' => 'handbook.show',
            'name' => 'Ver detalle de un manual',
            'description' => 'Ver en detalle cada manual del sistema'
        ]);
        $permission = Permission::create([//13
            'slug' => 'handbook.create',
            'name' => 'Creación de manuales',
            'description' => 'Podría crear nuevos manuales en el sistema'
        ]);
        $permission = Permission::create([//14
            'slug' => 'handbook.edit',
            'name' => 'Edición de mauales',
            'description' => 'Podría editar cualquier dato de un manual del sistema'
        ]);
        $permission = Permission::create([//15
            'slug' => 'handbook.destroy',
            'name' => 'Eliminar manual',
            'description' => 'Podría eliminar cualquier manual del sistema'
        ]);

        ////////////////////////////////////
        ////// Assigning Permissions //////
        //////////////////////////////////

        $rol_user->permissions()->sync([1,2,3,4,5]);//Rol User And Permission
        $rol_role->permissions()->sync([6,7,8,9,10]);//Rol Role And Permission
        $rol_handbook->permissions()->sync([11,12,13,14,15]);//Rol Handbook And Permission
        $rol_admin->permissions()->sync([1,2,3,4,5,11,12,13,14,15]);//Rol Admin And Permission
        $rol_root->permissions()->sync([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);//Rol Root And Permission
    }
}
