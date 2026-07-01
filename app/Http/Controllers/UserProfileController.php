<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{   // عرض بيانات المستخدم
    public function show(Request $request)
    {
        

        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

        $user = Auth::user();
        return response()->json([
            'user' => $user,
             'current_language' => app()->getLocale()
        ]);
    }

    // تحديث بيانات المستخدم
    public function update(Request $request)
    {
        \Log::info('Received file:', [
        'hasFile' => $request->hasFile('Avatar'),
        'fileValid' => $request->file('Avatar')?->isValid(),
        'clientOriginalName' => $request->file('Avatar')?->getClientOriginalName()
    ]);
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'Biography' => 'nullable|string|max:255',
            'Avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تحديث الاسم إذا تم تقديمه
        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }

        // تحديث السيرة الذاتية إذا تم تقديمها
        if ($request->has('Biography')) {
            $user->Biography = $validatedData['Biography'];
        }

        // معالجة صورة المستخدم إذا تم تحميلها
        if ($request->hasFile('Avatar')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($user->Avatar) {
                Storage::delete('public/avatars/' . $user->Avatar);
            }

            // حفظ الصورة الجديدة
            $avatar = $request->file('Avatar');
            $filename =uniqid().str_replace(' ', '_', $avatar->getClientOriginalName()); 
            $avatar->storeAs('public/avatars', $filename);
            $user->Avatar = $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

}