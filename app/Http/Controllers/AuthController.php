<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Badge;
use App\Models\UserBadge;
//use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|alpha_spaces_underscore',
            'email' => 'required|string|email|max:255|unique:users|regex:/^[a-zA-Z0-9.@]+$/',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
             // 'level_id' => 'required|exists:levels,id'
         
        ] ,[
    'email.strict_email' => 'الإيميل يجب أن يحتوي فقط على أحرف إنجليزية، أرقام، والنقاط أو شرطة قبل @',
]);




        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'TotalXP' => 0 ,
            'rank'=>0,
            'Type_id' => 1, 
            'level_id'=>1,
            'Avatar' => null, // إضافة قيمة افتراضية
            'Biography' => null, 
            'RegistrationDate'=>now(),
       
          
        ]);
           //اعطاءbadgesللمتستخدم عند تسجيل الدخول 
        $token = $user->createToken('auth_token')->plainTextToken;

         $firstBadge = Badge::find(1);

         if ($firstBadge) {
            UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $firstBadge->id,
            'AwardDate' => now(),
          ]);
        }
     
  
        return response()->json([
        'token' => $user->createToken('API_TOKEN')->plainTextToken,
        'message' => 'تم التسجيل بنجاح',
        'user' => $user,
        'TotalXP' => $user->TotalXP // يجب أن تكون 0
    ], 201);
    }
///////////////////////////////////////////////////////////////////////////////////
    public function login(Request $request)
    {
       
         $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    // الحصول على بيانات المستخدم بعد التأكد من نجاح عملية الدخول
    $user = Auth::user();
    
       return response()->json([
        'message' => 'تم التسجيل بنجاح',
        'token' => auth()->user()->createToken('API_TOKEN')->plainTextToken,
      
        'user' => $user


    ]);
        // $user = User::where('email', $request->email)->first();

        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return response()->json([
        //         'message' => 'Invalid credentials'
        //     ], 401);
        // }

        // $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //    // 'data' => new UserResource($user),
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => $user ,
        // ]);
   
     
    }
///////////////////////////////////////////////////////////////////////////////////
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    public function getUser(Request $request)
{
    return new UserResource($request->user()); // هنا
}
}