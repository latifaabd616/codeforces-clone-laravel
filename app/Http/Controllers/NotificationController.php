<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
//use Auth;

use App\Services\FirebaseService;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  /*   protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
      $this->firebaseService=$firebaseService;
    } */
/*     public function sendPushNotification(Request $request)
    {
        $request->validate([
            'token'=>'required|string',
            'title'=>'required|string',
            'body'=>'required|string',
            'data'=>'nullable|array',
        ]);
        $token=$request->input('token');
        $title=$request->input('title');
        $body=$request->input('body');
        $data=$request->input( 'data',[]);
        $this->firebaseService->sendNotification($token,$title, $body,$data);
        return response()->json(['message'=>'notification sent successfully']);

    } */

///////////////////////////



    public function store(Request $request)
    {
        // التحقق من أن المستخدم مسؤول
/* 
        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
             } */

    // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);

    // التحقق من البيانات
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'Title' => 'required|string|max:255',
        'Description' => 'required|string',
        'ReceiveDate' => 'sometimes|date',
        'Is_read' => 'sometimes|boolean',
        'title_translations' => 'sometimes|array',
        'title_translations.en' => 'required_with:title_translations|string',
        'title_translations.ar' => 'required_with:title_translations|string',
        'description_translations' => 'sometimes|array',
        'description_translations.en' => 'required_with:description_translations|string',
        'description_translations.ar' => 'required_with:description_translations|string',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'أخطاء في التحقق',
                'errors' => $validator->errors()
            ], 422);
        }

   
    // إنشاء الإشعار
    $notificationData = [
        'user_id' => $request->user_id, // تأكد من وجود user_id
        'Title' => $request->Title,
        'Description' => $request->Description,
        'ReceiveDate' => $request->ReceiveDate?? now(),
        'Is_read' => $request->Is_read ?? false
    ];
        
  // إضافة الترجمات إذا وجدت
    if ($request->has('title_translations')) {
        $notificationData['title_translations'] = $request->title_translations;
    } else {
        $notificationData['title_translations'] = [
            'en' => $request->Title,
            'ar' => $request->Title // ترجمة افتراضية
        ];
    }

    if ($request->has('description_translations')) {
        $notificationData['description_translations'] = $request->description_translations;
    } else {
        $notificationData['description_translations'] = [
            'en' => $request->Description,
            'ar' => $request->Description // ترجمة افتراضية
        ];
    }
    $notification = Notification::create($notificationData);

        return response()->json([
            'message' => 'تم إنشاء الإشعار بنجاح',
            'data' => $notification,
             'current_language' => app()->getLocale()
        ], 201);
    }

 


        // جلب الإشعارات غير المقروءة مع العدد
    public function getUnreadNotifications(Request $request)
    {
            // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
          $locale = $request->header('Accept-Language', 'en');
         app()->setLocale($locale);

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            
            }
             $user = $request->user();

     
            // جلب الإشعارات غير المقروءة
            $unreadNotifications = Notification::where('user_id', $user->id)
               // ->where('Is_read', false)
                ->orderBy('ReceiveDate', 'desc')
                ->get();

            // حساب العدد
            
           /*  $unreadCount = $unreadNotifications->count();
            // إذا كان هناك إشعارات غير مقروءة، نحددها كمقروءة
            if ($unreadCount > 0) {
                Notification::where('user_id', $user->id)
                    ->where('Is_read', false)
                    ->update(['is_read' => true]);
            }  */

            return response()->json([
                'success' => true,
                
                'data' => [
                    'notifications' => $unreadNotifications,
                    //'unread_count' => $unreadCount,
                    'current_language' => app()->getLocale()
                ],
               // 'success' => $allNotifications
            ]);

        
           
        
    }
        //جلب  عدد الإشعارات  غير المقروءةد
    public function getUnreadNotificationscount(Request $request)
    {
        
            $user = auth()->user();

            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            
            }
             $user = $request->user();


      
            // جلب الإشعارات غير المقروءة
            $unreadNotifications = Notification::where('user_id', $user->id)
                ->where('Is_read', false)
                //->orderBy('ReceiveDate', 'desc')
                ->get();

            // حساب العدد
            
            $unreadCount = $unreadNotifications->count();
          

            return response()->json([
                'success' => true,
                
                'data' => [
                   
                    'unread_count' => $unreadCount
                ],
               // 'success' => $allNotifications
            ]);

        
           
        
    }


   public function showAndMarkAsRead($id)
{
    // الحصول على المستخدم المصادق عليه
    $user = auth()->user();
    
    // البحث عن الإشعار الذي يخص هذا المستخدم
    $notification = Notification::where('id', $id)
                      ->where('user_id', $user->id)
                      ->first();

    // التحقق من وجود الإشعار
    if (!$notification) {
        return response()->json([
            'message' => 'الإشعار غير موجود أو لا تملك صلاحية الوصول إليه'
        ], 404);
    }

    // تحديث حالة القراءة إذا لم تكن مقروءة بالفعل
    if (!$notification->Is_read) {
        $notification->update(['Is_read' => true]);
        
        // يمكنك أيضًا إضافة تحديث لعدد الإشعارات غير المقروءة للمستخدم إذا كان لديك هذا الحقل
        // $user->unread_notifications_count = $user->notifications()->where('Is_read', false)->count();
        // $user->save();
    }

    // إرجاع بيانات الإشعار
    return response()->json([
        'message' => 'تم جلب الإشعار وتحديث حالة القراءة',
        'data' => $notification
    ], 200);

}
    public function markAsRead(Request $request, $id)
    {
        // البحث عن الإشعار
        $notification = Notification::find($id);

        // التحقق من وجود الإشعار
        if (!$notification) {
            return response()->json([
                'message' => 'الإشعار غير موجود'
            ], 404);
        }

        // تحديث حالة القراءة
        $notification->update(['Is_read' => true]);

        // إرجاع رسالة النجاح
        return response()->json([
            'message' => 'تم تحديث حالة الإشعار إلى مقروء'
        ], 200);
    }
/////////////////////////////////////////
    public function index()
    {
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

  

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
