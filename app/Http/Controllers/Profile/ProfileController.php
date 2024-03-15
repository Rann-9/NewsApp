<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $title = 'My Profile';
        return view('home.profile.index', compact(
            'title'
        ));
    }

    public function changePassword()
    {
        $title = 'Change Password';
        return view('home.profile.change-password', compact(
            'title'
        ));
    }

    public function updatePassword(Request $request)
    {
        // validate
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:6',
            'confirmation_password' => 'required|min:6'
        ]);

        // check current password status
        $currentPasswordStatus = Hash::check(
            $request->current_password,
            auth()->user()->password
        );

        if ($currentPasswordStatus) {
            if ($request->password == $request->confirmation_password) {
                // get user login by auth
                $user = auth()->user();

                // update password
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->back()->with(
                    'success',
                    'password has been updated'
                );
            } else {
                return redirect()->back()->with(
                    'error',
                    'password does not match'
                );
            }
        } else {
            return redirect()->back()->with('error', 'Current password is wrong');
        }
    }

    public function allUser()
    {
        $title = 'All User';
        $user = User::where('role', 'user')->get();

        return view('home.user.index', compact(
            'title',
            'user'
        ));
    }

    public function resetPassword($id)
    {
        // get user by id
        $user = User::find($id);
        $user->password = Hash::make('123456');
        $user->save();

        return redirect()->back()->with(
            'success',
            'Password has been reset'
        );
    }

    public function createProfile()
    {
        $title = 'Create Profile';

        return view('home.profile.create', compact(
            'title'
        ));
    }

    public function storeProfile(Request $request)
    {
        // validate
        $this->validate($request, [
            'first_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // store image
        $image = $request->file('image');
        $image->storeAs('public/profile', $image->getClientOriginalName());

        // get user login
        $user = auth()->user();

        // create data profile
        $user->profile()->create([
            'first_name' => $request->first_name,
            'image' => $image->getClientOriginalName()
        ]);

        return redirect()->route('profile.index')->with(
            'success',
            'Profile has been created'
        );
    }

    public function editProfile()
    {
        $title = 'Edit Profile';

        // get data user login
        $user = auth()->user();

        return view('home.profile.edit', compact(
            'title',
            'user'
        ));
    }

    public function updateProfile(Request $request)
    {
        // validate
        $this->validate($request, [
            'first_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // get user login
        $user = auth()->user();

        // cek kondisi image bila tidak upload
        if ($request->file('image') == '') {
            $user->profile->update([
                'first_name' => $request->first_name
            ]);

            return redirect()->route('profile.index')->with(
                'success',
                'Profile has been created'
            );
        } else {
            // delete image
            Storage::delete('public/profile/' .basename($user->profile->image));

            // sotre image
            $image = $request->file('image');
            $image->storeAs('public/profile', $image->getClientOriginalName());

            // update data
            $user->profile->update([
                'first_name' => $request->first_name,
                'image' => $image->getClientOriginalName()
            ]);

            return redirect()->route('profile.index')->with(
                'success',
                'Profile has been created'
            );
        }
    }
}
