<?php

namespace App\Http\Controllers;

use App\Modules\IUserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    private $user;

    public function __construct(IUserManagement $user)
    {
        $this->user = $user;
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            }

            $data = $request->all();

            if (!empty($request->file('avatar'))) {
                $fileName = $request->file('avatar')->getClientOriginalName();

                $image = $request->file('avatar');
                $input['imagename'] = time() . '.' . $image->extension();
                $filePath = storage_path('images');

                $img = Image::make($image->path());
                $img->resize(256, 256)->save($filePath . '/' . $input['imagename']);

                $data['avatar'] = $fileName;
            }

            if ($this->user->update($id, $data)) {
                return response()->json(['message' => 'User updated successfully'], 200);
            }

            return response()->json(['message' => 'User updating failed'], 400);
        } catch(\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }

    }
}
