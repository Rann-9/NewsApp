<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // cek credentials (login)
            $credentials = request(['email', 'password']);
            if (!Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ])) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            };

            // cek jika password tidak sesuai
            $user = User::where('email', $credentials['email'])->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            // jika berhasil cek password maka loginkan
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated', 200);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6'
            ]);

            // cek kondisi jika password dan confirm password tidak sama (!= artinya tidak sama)
            if ($request->password != $request->confirm_password) {
                return ResponseFormatter::error([
                    'message' => 'Password not match'
                ], 'Authentication Failed', 401);
            }

            // create akun
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // get data akun
            $user = User::where('email', $request->email)->first();

            // create token
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated', 200);
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function updatePassword(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'old_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6'
            ]);

            // get data user
            $user = Auth::user();

            // cek password lama
            if (!Hash::check($request->old_password, $user->password)) {
                return ResponseFormatter::error([
                    'message' => 'Password Lama Tidak Sesuai'
                ], 'Authentication Failed', 401);
            }

            // cek password baru dan konfirmasi password baru
            if ($request->new_password != $request->confirm_password) {
                return ResponseFormatter::error([
                    'message' => 'Password Tidak Sesuai'
                ], 'Authentication Failed', 401);
            }

            // update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ResponseFormatter::success([
                'message' => 'Password Berhasil Diubah'
            ], 'Authenticated', 200);
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function allUsers()
    {
        $users = User::where('role', 'user')->get();
        return ResponseFormatter::success($users, 'Data user berhasil di ambil');
    }

    public function storeProfile(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'first_name' => 'required',
                'image' => 'required|image|max:2048|mimes:jpg,png,jpeg'
            ]);

            // get data user
            $user = auth()->user();

            // upload image
            $image = $request->file('image');
            $image->storeAs('public/profile', $image->hashName());

            // create profile
            $user->profile()->create([
                'first_name' => $request->first_name,
                'image' => $image->hashName()
            ]);

            // get data profile
            $profile = $user->profile;

            return ResponseFormatter::success(
                $profile,
                'Profile Berhasil Diupdate'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something Went Wrong',
                'error' => $error
            ], 'Authentication', 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'first_name' => '',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // get data user
            $user = auth()->user();

            // cek jika user belum ada profile maka harus membuat profile terlebih dahulu
            if (!$user->profile) {
                return ResponseFormatter::error([
                    'message' => 'Profile not found, please create profile first'
                ], 'Authentication Failed', 404);
            }

            if ($request->file('image') == '') {
                $user->profile->update([
                    'first_name' => $request->first_name
                ]);
                return ResponseFormatter::success([
                    'message' => 'First Name Berhasil di ubah'
                ], 'Authentication', 200);
            } else {

                if ($request->first_name == '') {
                    Storage::delete('public/profile/' . basename($user->profile->image));

                    // Store Image
                    $image = $request->file('image');
                    $image->storeAs('public/profile', $image->getClientOriginalName());
                    $user->profile->update([
                        $image = $request->file('image')
                    ]);
                } else {
                    // delete Image
                    Storage::delete('public/profile/' . basename($user->profile->image));

                    // Store Image
                    $image = $request->file('image');
                    $image->storeAs('public/profile', $image->getClientOriginalName());

                    // Update data
                    $user->profile->update([
                        'first_name' => $request->first_name,
                        'image' => $image->getClientOriginalName()
                    ]);
                }
            }

            // get data profile
            $profile = $user->profile;

            return ResponseFormatter::success(
                $profile,
                'Profile Berhasil Diupdate'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something Went Wrong',
                'error' => $error
            ], 'Authentication', 500);
        }
    }
}
