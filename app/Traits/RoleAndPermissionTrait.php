<?php
namespace App\Traits;

trait RoleAndPermissionTrait
{
    public function havePermission($permission)
    {
		try {
	        foreach ($this->roles as $role) {
	            if($role["full-access"] == 'yes'){
	                return true;
	            }
	            foreach ($role->permissions as $permissions) {
	                if($permissions->slug == $permission){
	                    return true;
	                }
	            }
	        }
	        return false;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'RolesAndPermissionTrait.havePermission.failed',
                'error' => $e->getMessage()
            ], 505);
		}
    }
}
?>