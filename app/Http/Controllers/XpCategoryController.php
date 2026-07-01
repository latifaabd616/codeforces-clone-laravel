<?php

namespace App\Http\Controllers;

use App\Models\Xpcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class XpCategoryController extends Controller
{
    ///////crad xpcategory by admain
       public function index(Request $request)
    {
        /* if (!Auth::user()->Is_Admin) {
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

        $categories = XpCategory::all();
      //  return response()->json($categories);

       return response()->json([
        'message' => 'Successfully',
        'data' => $categories,
        'current_language' => app()->getLocale(),
       
    ]);
    }
    
    // إنشاء فئة جديدة
    public function store(Request $request)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

      $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'Icon' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
        ]);
        // معالجة وتخزين الصورة
         $filename = null;
        if ($request->hasFile('Icon')) {
        $icon = $request->file('Icon');
        $filename = uniqid() . str_replace(' ', '_', $icon->getClientOriginalName()); 
        $icon->storeAs('public/xpcategory', $filename);
    }

    
        // إنشاء المستوى الجديد
    $xpcategory = XpCategory::create([
        'Title' => $validated['Title'],
        'Icon' => $filename ? '' . $filename : null,
         'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['title_translations'],
            'ar' => $validated['title_translations'] // ترجمة افتراضية
        ],
    ]);

       // return response()->json($xpcategory, 201);

        return response()->json([
        'message' => ' successfully',
        'data' =>$xpcategory
       ]);
    }


       // عرض فئة محددة
    public function show(Request $request,$id)
    {
       /*  if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
         */
       // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
       app()->setLocale($locale);

        $xpcategory = XpCategory::findOrFail($id);
        //return response()->json($xpcategory);

       return response()->json([
        'message' => 'Successfully',
        'data' => $xpcategory,
        'current_language' => app()->getLocale()
       ]);
    }

    /**
     * Update the specified resource in storage.
     */
   // تحديث فئة
    public function update(Request $request, $id)
    {  \Log::info('Update request received', $request->all());
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

            // العثور على المستوى المطلوب أو إرجاع 404
     $xpcategory = XpCategory::findOrFail($id);
    \Log::info('Before update', $xpcategory->toArray());

       $validated = $request->validate([
            'Title' => 'sometimes|string|max:100',
            'Icon' =>'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
        ]);

        // تحديث الحقول النصية
    if ($request->has('Title')) {
         $xpcategory->Title = $validated['Title'];
    }
    // تحديث الترجمات إذا تم تقديمها
     if ($request->has('title_translations')) {
           $xpcategory->title_translations = $validated['title_translations'];
     }
        
    
    // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if (  $xpcategory->Icon) {
              
                   Storage::delete('public/xpcategory/' .    $xpcategory->Icon);
            }


        // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/xpcategory', $filename);
          $xpcategory->Icon = $filename;
        }

     
      $xpcategory->update([
       'Title' => $request->input('Title',  $xpcategory->Title),
        'Icon' => $filename ??  $xpcategory->Icon,
         'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
           ]);
    \Log::info('After update',  $xpcategory->toArray());
    // إعادة تحميل الموديل من قاعدة البيانات للتأكد
     $xpcategory->refresh();
    \Log::info('After refresh', $xpcategory->toArray());
    
     return response()->json([
        'message' => 'Level updated successfully',
        'data' => $xpcategory
    ]);
   /*      $category = XpCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category); */
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Xpcategory $xpcategory)
    {
        //
    }
      /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function edit(Xpcategory $xpcategory)
    {
        //
    }

}
