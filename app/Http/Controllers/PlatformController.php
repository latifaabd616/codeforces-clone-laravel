<?php

namespace App\Http\Controllers;

use App\Models\platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      // GET جميع السجلات
      public function index(Request $request)
      {
        /* if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        } */
          // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    
    // التحقق من أن اللغة مدعومة
    $supportedLocales = ['en', 'ar'];
    if (in_array($locale, $supportedLocales)) {
        app()->setLocale($locale);
    } else {
        app()->setLocale('en'); // اللغة الافتراضية
    }

    $platforms = Platform::all();
     // return response()->json($platforms);

     return response()->json([
        'message' => ' successfully',
        'data' => $platforms,
         'current_language' => app()->getLocale()
       ]);
      }

    

 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'Title' => 'required||string|max:100',
            'Icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
        ]);

        // معالجة وتخزين الصورة
        $filename = null;
        if ($request->hasFile('Icon')) {
        $icon = $request->file('Icon');
        $filename = uniqid() . str_replace(' ', '_', $icon->getClientOriginalName()); 
        $icon->storeAs('public/platform', $filename);
          }

           
        // إنشاء المستوى الجديد
       $platform = Platform::create([
        'Title' => $validated['Title'],
        'Icon' => $filename ? '' . $filename : null,
        'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['Title'],
            'ar' => $validated['Title'] // ترجمة افتراضية
        ],
    ]);
    // return response()->json( $platform , 201);

     return response()->json([
        'message' => 'successfully',
        'data' => $platform
    ]);



    }



    /**
     * Display the specified resource.
     */
     // GET عرض سجل محدد
     public function show(Request $request,$id)
     { 
         /*  if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
             } */
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
         $locale = $request->header('Accept-Language', 'en');
         app()->setLocale($locale);
         $platform = Platform::find($id);
         
        // return response()->json($platform);

         return response()->json([
        'message' => 'successfully',
        'data' => $platform,
        'current_language' => app()->getLocale()
         ]);
     }

 
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(platform $platform)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     // PUT تحديث سجل
     public function update(Request $request, $id)
     {

        \Log::info('Update request received', $request->all());
        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
           }
         // العثور على المنصة المطلوبة أو إرجاع 404
         $platform = Platform::find($id);
           \Log::info('Before update',  $platform ->toArray());
        
 
        $validated = $request->validate( [
             'Title' => 'sometimes|required|max:100',
             'Icon' =>  'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
             'title_translations' => 'sometimes|array',
             'title_translations.en' => 'required_with:title_translations|string',
             'title_translations.ar' => 'required_with:title_translations|string',
            ]);
          // تحديث الحقول النصية
    if ($request->has('Title')) {
        $platform ->Title = $validated['Title'];
    }
       if ($request->has('title_translations')) {
          $platform ->title_translations = $validated['title_translations'];
     }

    
     // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ( $platform->Icon) {
              
                   Storage::delete('public/platform/' .   $platform->Icon);
            }
            
                     // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/platform', $filename);
        $platform->Icon = $filename;
        }
          

        $platform->update([
       'Title' => $request->input('Title', $platform->Title),
       
        'Icon' => $filename ?? $platform->Icon,
          'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
           ]);
                 \Log::info('After update', $platform->toArray());

                 
  

              // إعادة تحميل الموديل من قاعدة البيانات للتأكد
   $platform->refresh();
    \Log::info('After refresh', $platform->toArray());
    
     return response()->json([
        'message' => ' successfully',
        'data' => $platform
    ]);
  
 
     /*     if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         $platform->update($request->all());
         return response()->json($platform); */
     }



  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $platform = Platform::find($id);
        if (!$platform) {
            return response()->json(['message' => 'Platform not found'], 404);
        }

        $platform->delete();
        return response()->json(['message' => 'Platform deleted successfully']);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
}
