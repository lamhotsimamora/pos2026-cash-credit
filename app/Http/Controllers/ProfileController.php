<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function load(){
        $profile = \App\Models\Profile::where('id',1)->get();
        return $this->responseSuccess('Profile loaded successfully', $profile[0]);
    }

    public function update(Request $request){
        $profile = Profile::find(1);

        $profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->email = $request->input('email');
        $profile->hp = $request->input('hp');

        $profile->save();
         return $this->responseSuccess('Profile updated successfully', null);
    }

    public function changePassword(Request $request){
        $data=Admins::where('username', 'admin')->where('password',md5($request->input('old_password')))->count();
        
        if ($data==1){
            $admin = Admins::find(1);
            $admin->password = md5($request->input('confirm_password'));

            $admin->save();
            return $this->responseSuccess('Password Has Been Saved', null);
        }else{
            return $this->responseError('Old Password is Wrong', null);
        }
    }
}
