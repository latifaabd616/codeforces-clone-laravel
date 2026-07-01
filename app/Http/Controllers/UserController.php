<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  public function store(Request $request) {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

 //اظهار البروفايل
     // جلب معلومات مستخدم معين
    public function show(Request $request,$id)
    {
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

        $user = User::with(['level', 'type','badges'])->find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'current_language' => app()->getLocale()
        ]);
    }
     /**
     * حذف حساب المستخدم
     */
 public function destroy(Request $request)
{
    $user = $request->user();
    
    // حذف جميع tokens المستخدم أولاً
    $user->tokens()->delete();
    
    // ثم حذف المستخدم
    $user->delete();
    
    return response()->json([
        'message' => 'تم حذف الحساب بنجاح'
    ], 200);
}

  

   /*  // جلب معلومات جميع المستخدمين مع مستوياتهم وأنواعهم
    public function index()
    {
        $users = User::with(['level', 'type'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    } */


  
  

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,'.$user->id,
        'level_id' => 'sometimes|exists:levels,id',
        'avatar' => 'sometimes|image|max:2048'
    ]);

    if ($request->hasFile('avatar')) {
        // احذف الصورة القديمة إذا وجدت
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }
        $validated['avatar'] = $request->file('avatar')->store('avatars');
    }

    $user->update($validated);
    return new UserResource($user->load('level'));
}
/* public function destroy(User $user)
{
    // التحقق من وجود مستخدم مسجل الدخول
    if (!auth()->check()) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // استخدام null coalescing operator للتحقق من null
    if (!(auth()->user()->Is_Admin ?? false) && auth()->id() !== $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $user->delete();
    return response()->noContent();
} */
////make  the user admin
    public function makeAdmin(Request $request, $id)
    {
        // التحقق من أن المستخدم الحالي هو مشرف
        if (!$request->user()->Is_Admin) {
            return response()->json([
                'message' => 'غير مصرح بهذه العملية'
            ], 403);
        }

        // العثور على المستخدم المطلوب
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        // تحديث حالة المشرف
        $user->update([
            'Is_Admin' => true
        ]);

        return response()->json([
            'message' => 'تم تغيير حالة المستخدم إلى مشرف بنجاح',
            'user' => $user
        ]);
    }
     public function makeuser(Request $request, $id)
    {
        // التحقق من أن المستخدم الحالي هو مشرف
        if (!$request->user()->Is_Admin == 2) {
            return response()->json([
                'message' => 'غير مصرح بهذه العملية'
            ], 403);
        }

        // العثور على المستخدم المطلوب
        $user = User::find($id);
            // منع المستخدم من تعديل صلاحيات نفسه
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'لا يمكن تعديل صلاحيات حسابك الشخصي'
                ], 200);
            }

        if (!$user) {
            return response()->json([
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        // تحديث حالة المشرف
        $user->update([
            'Is_Admin' => false
        ]);

        return response()->json([
            'message' => 'تم تغيير حالة المستخدم إلى مشرف بنجاح',
            'user' => $user
        ]);
    }
   public function index(Request $request)
    {  // التحقق من أن المستخدم الحالي هو مشرف
        if (!$request->user()->Is_Admin) {
            return response()->json([
                'message' => 'غير مصرح بهذه العملية'
            ], 403);
        }

        // جلب جميع المستخدمين الذين ليسوا مسؤولين (Is_Admin = false)
        $users = User::where('Is_Admin', false)->get();
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // التحقق من أن المستخدم الحالي هو المالك (Is_Admin = 2)
 /*    public function getAdmins(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بالوصول. يجب أن تكون المالك (Is_Admin = 2).'
            ], 403);
        }

        // جلب جميع المستخدمين الذين لديهم صلاحية مدير (Is_Admin = 1 أو 2)
        $admins = User::whereIn('Is_Admin', [1, 2])
                     ->select('id', 'name', 'email', 'Avatar', 'Biography', 'RegistrationDate', 'Is_Admin', 'TotalXP', 'rank')
                     ->get();

        // إرجاع النتيجة
        return response()->json([
            'success' => true,
            'admins' => $admins
        ], 200);
    } */
   /**
     * جلب جميع المستخدمين الإداريين
     */
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
