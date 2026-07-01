<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use App\Http\Resources\BadgeResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 


class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
        $locale = $request->header('Accept-Language', 'en');
        
        // التحقق من أن اللغة مدعومة
        $supportedLocales = ['en', 'ar'];
        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en'); // اللغة الافتراضية
        }
        
        $badges = Badge::all();
        $count = Badge::count();
        
        return response()->json([
            'message' => 'this all information json',
            'data' => $badges,
            'total_badges' => $count,
            'current_language' => app()->getLocale() // إضافة اللغة الحالية للرد
        ], 200);
         
    }


    
///crad by admin
    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    { 
       
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated=$request->validate([
            'Title' => 'required|string|max:100',
            'Criteria' => 'required|string',
            'Icon' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB كحد أقصى
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
            'criteria_translations' => 'sometimes|array',
            'criteria_translations.en' => 'required_with:criteria_translations|string',
            'criteria_translations.ar' => 'required_with:criteria_translations|string',
        ]);


        
            // معالجة وتخزين الصورة
        $filename = null;
        if ($request->hasFile('Icon')) {
              $icon = $request->file('Icon');
              $filename = uniqid() . str_replace(' ', '_', $icon->getClientOriginalName()); 
              $icon->storeAs('public/badges', $filename);
        }
    
       // إنشاء المستوى الجديد
        $badge = Badge::create([
        'Title' => $validated['Title'],
        'Criteria' => $validated['Criteria'],
        'Icon' => $filename ? '' . $filename : null,
        'title_translations' => $validated['title_translations'] ?? [
                'en' => $validated['title_translations']['en'],
                'ar' => $validated['title_translations']['ar']
            ],
        'criteria_translations' => $validated['criteria_translations'] ?? [
                'en' => $validated['criteria_translations']['en'],
                'ar' => $validated['criteria_translations']['ar']
            ],
    ]);

         return response()->json($badge, 201);
    }

    /**
     * Display the specified resource.
     */
     public function show(Request $request,$id)
    {
        
    /*     if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
           } */
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
        $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

        $badge = Badge::findOrFail($id);
         return response()->json([
            'data' => $badge,
            'current_language' => app()->getLocale()
        ]);
      
     
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
         \Log::info('Update request received', $request->all());

         if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
          }

    // العثور على الهدية المطلوبة أو إرجاع 404
     $badge = Badge::findOrFail($id);
    \Log::info('Before update', $badge->toArray());

       $validated= $request->validate([
            'Title' => 'sometimes|string|max:100',
            'Criteria' => 'sometimes|string',
            'Icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
            'criteria_translations' => 'sometimes|array',
            'criteria_translations.en' => 'required_with:criteria_translations|string',
            'criteria_translations.ar' => 'required_with:criteria_translations|string',
        ]);
         
    // تحديث الحقول النصية
    if ($request->has('Title')) {
        $badge->Title = $validated['Title'];
    }
    
    if ($request->has('Criteria')) {
        $badge->Criteria = $validated['Criteria'];
    }
    
    // تحديث الترجمات إذا تم تقديمها
     if ($request->has('title_translations')) {
         $badge->title_translations = $validated['title_translations'];
     }
        
    if ($request->has('criteria_translations')) {
        $badge->criteria_translations = $validated['criteria_translations'];
     }

       // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ($badge->Icon) {
              
                   Storage::delete('public/badges/' .  $badge->Icon);
            }

           // حفظ الصورة الجديدة
           $icon = $request->file('Icon');
           $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
           $icon->storeAs('public/badges', $filename);
           $badge->Icon = $filename;
    }
      // $badge->save(); 
   $badge->update([
       'Title' => $request->input('Title', $badge->Title),
       'Criteria' => $request->input('Criteria', $badge->Criteria),
        'Icon' => $filename ?? $badge->Icon,
        'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
        'criteria_translations' => $request['criteria_translations'] ?? [
                'en' => $request['criteria_translations']['en'],
                'ar' => $request['criteria_translations']['ar']
            ],
           ]);
           
    \Log::info('After update', $badge->toArray());

     // إعادة تحميل الموديل من قاعدة البيانات للتأكد
    $badge->refresh();
    \Log::info('After refresh', $badge->toArray());
    
     return response()->json([
        'message' => 'badge updated successfully',
        'data' => $badge
    ]);


      
    }





    /**
     * Remove the specified resource from storage.
     */
        public function destroy($id)
    {
       
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $badge = Badge::findOrFail($id);
        $badge->delete();

        return response()->json(['message' => 'Badge deleted successfully']);
    }
     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        //
    }
}
