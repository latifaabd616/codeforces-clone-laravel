<?php

namespace App\Http\Controllers;

use App\Models\Framework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FrameworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    ////////////////////////
        // عرض جميع الفريموركس
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


        $framework = Framework::all();
        //return response()->json($framework);

 return response()->json([
        'message' => 'Successfully',
        'data' => $framework,
        'current_language' => app()->getLocale()
    ]);
    }

        // إنشاء فريمورك جديد
    public function store(Request $request)
    {
        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         $validated =$request->validate([
            'title' => 'required|string|max:255',
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
        $icon->storeAs('public/framework', $filename);
    }

    
        // إنشاء المستوى الجديد
    $framework = Framework::create([
        'title' => $validated['title'],
        'Icon' => $filename ? '' . $filename : null,
         'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['title_translations'],
            'ar' => $validated['title_translations'] // ترجمة افتراضية
        ],
    ]);
     //return response()->json($framework , 201);
        return response()->json([
        'message' => ' successfully',
        'data' => $framework
    ]);

    }


   

       // عرض فريمورك معين
    public function show(Request $request,$id)
    {
       /*  if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        } */
            // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);

  $framework = Framework::findOrFail($id);
        //return response()->json($framework);
        return response()->json([
        'message' => 'Successfully',
        'data' => $framework,
        'current_language' => app()->getLocale()
    ]);
    }


   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

   

       // تحديث فريمورك
    public function update(Request $request, $id)
    { \Log::info('Update request received', $request->all());
        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

          // العثور على المستوى المطلوب أو إرجاع 404
     $framework = Framework::findOrFail($id);
    \Log::info('Before update', $framework ->toArray());

         $validated =$request->validate([
            'Title' => 'sometimes|string|max:255',
            'Icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
        ]);
         // تحديث الحقول النصية
    if ($request->has('Title')) {
        $framework->Title = $validated['Title'];
    }
    if ($request->has('title_translations')) {
        $framework->title_translations = $validated['title_translations'];
     }
     // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ($framework->Icon) {
              
                   Storage::delete('public/framework/' .  $framework->Icon);
            }

             // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/framework', $filename);
        $framework->Icon = $filename;
        }

        $framework->update([
       'Title' => $request->input('Title', $framework->Title),
       
        'Icon' => $filename ?? $framework->Icon,
        'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
           ]);
    \Log::info('After update', $framework->toArray());
    // إعادة تحميل الموديل من قاعدة البيانات للتأكد
   $framework->refresh();
    \Log::info('After refresh', $framework->toArray());
    
     return response()->json([
        'message' => ' successfully',
        'data' => $framework
    ]);
  
       /*  $framework->update($request->all());
        return response()->json($framework); */
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Framework $framework)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Framework $framework)
    {
        //
    }
}
