<?php

namespace App\Http\Controllers;
use App\Models\Users;

class RoleController extends Controller
{

	public function role($id){
		$user = Users::where('id',$id)->first();
		return $user->role;
	}


}