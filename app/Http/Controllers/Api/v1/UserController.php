<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\UserTyping;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers;
use App\Service\FireBase\FirebaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{


    protected const PATH_FILE = "users";

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'bio' => 'nullable|string|max:4000',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $mimeType = $request->file('photo')->getMimeType();
            $mediaType = Helpers::getMediaTypeFromMimeType($mimeType);
            $firebaseUrl = app(FirebaseStorageService::class)->handle($file, $mediaType, self::PATH_FILE);
            $user->photo = $firebaseUrl;
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
