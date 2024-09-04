<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\UserTyping;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:4000',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete($user->photo);
            }
            // Store new photo
            $path = $request->file('photo')->store('profile_pictures', 'public');
            $user->photo = $path;
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], config('request.rsp_success'));
    }

    public function updateTypingStatus(Request $request)
    {
        $user = $request->user();
        $isTyping = $request->is_typing;

        $user->is_typing = $isTyping;
        $user->save();

        broadcast(new UserTyping($user->id, $isTyping));

        return response()->json(['message' => 'Typing status updated']);
    }


}
