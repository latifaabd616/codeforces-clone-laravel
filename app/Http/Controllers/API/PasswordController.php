<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
      
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }
   //////////////
     public function requestReset(Request $request)
    {
        // التحقق من صحة الإيميل
        $request->validate(['email' => 'required|email|exists:users,email']);

     
        
        // إنشاء رمز بسيط مكون من 6 أرقام
        $resetCode = rand(100000, 999999);
    
        
        // هنا يمكنك إرسال الرمز عبر البريد أو SMS
        // mail($request->email, 'رمز إعادة التعيين', 'رمزك: ' . $resetCode);
        
        return response()->json([
            'message' => 'تم إرسال رمز إعادة التعيين إلى بريدك الإلكتروني',
            'code' => $resetCode // في الواقع الفعلي، لا نرسل الرمح في الرد لأسباب أمنية
        ]);
    }
     /**
     * التحقق من الرمز وإعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {     
           
    $validator = Validator::make($request->all(), [
        'email' => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
    ]);

          if ($validator->fails()) {
        return response()->json([
            'message' => 'بيانات غير صحيحة',
            'errors' => $validator->errors()
        ], 422);
        }

          $user = User::where('email', $request->email)->first();

    // التحقق من وجود المستخدم
    if (!$user) {
        return response()->json([
            'message' => 'المستخدم غير مسجل الدخول'
        ], 401);
       }
     
    if ($request->password != $request->password_confirmation) {
            return response()->json([
                'message' => 'كلمة المرور الحالية للتأكيد غير صحيحة'
            ], 422);
     }

    // تحديث كلمة المرور
     
    $user->update([
            'password' => Hash::make($request->password)
    ]);

     return response()->json([
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
     ]);
      
   
         
/* 
      
        $user->update([
            'password' => Hash::make($request->new_password)
        ]); */
    }
      public function getAdmins(Request $request)
    {
        // التحقق من أن المستخدم الحالي هو المالك (Is_Admin = 2)
        if (Auth::user()->Is_Admin != 2) {
            return response()->json([
                'message' => 'غير مصرح بهذه العملية'
            ], 403);
        }

        // جلب جميع المستخدمين الإداريين (Is_Admin = 1)
        $admins = User::where('Is_Admin', 1)->get();

        return response()->json([
            'admins' => $admins
        ], 200);
    }
    /**
     * جلب مستخدم إداري محدد
     */
    public function getAdmin(Request $request, $id)
    {
        // التحقق من أن المستخدم الحالي هو المالك (Is_Admin = 2)
        if (Auth::user()->Is_Admin != 2) {
            return response()->json([
                'message' => 'غير مصرح بهذه العملية'
            ], 403);
        }

        // البحث عن المستخدم الإداري
        $admin = User::where('Is_Admin', 1)->find($id);

        if (!$admin) {
            return response()->json([
                'message' => 'المستخدم الإداري غير موجود'
            ], 404);
        }

        return response()->json([
            'admin' => $admin
        ], 200);
    }


}

