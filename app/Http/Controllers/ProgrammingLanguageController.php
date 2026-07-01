<?php

namespace App\Http\Controllers;

use App\Models\ProgrammingLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgrammingLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       /*  if (!auth()->user()->Is_Admin) {
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

         $languages = ProgrammingLanguage::all();
       // return response()->json($languages);
         return response()->json([
        'message' => ' successfully',
        'data' => $languages,
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
        $icon->storeAs('public/language', $filename);
    }

        
        // إنشاء المستوى الجديد
    $language = ProgrammingLanguage ::create([
        'Title' => $validated['Title'],
        'Icon' => $filename ? '' . $filename : null,
         'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['title_translations'],
            'ar' => $validated['title_translations'] // ترجمة افتراضية
        ],
    ]);
    // return response()->json( $language , 201);
          return response()->json([
        'message' => ' successfully',
        'data' => $language
    ]);


  /*       $language = ProgrammingLanguage::create($validated);
        return response()->json($language, 201); */
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id)
    {
        /*   if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        } */
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);
        
        $language = ProgrammingLanguage::findOrFail($id);
        //return response()->json($language);
             return response()->json([
        'message' => ' successfully',
        'data' => $language,
        'current_language' => app()->getLocale()

    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    { 
        \Log::info('Update request received', $request->all());
        if (!auth()->user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
                $language = ProgrammingLanguage::findOrFail($id);
                 \Log::info('Before update', $language ->toArray());

        $validated = $request->validate([
            'Title' => 'sometimes|string|max:100',
            'Icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
        ]);
     // تحديث الحقول النصية
    if ($request->has('Title')) {
        $language->Title = $validated['Title'];
    }
     if ($request->has('title_translations')) {
        $language->title_translations = $validated['title_translations'];
     }

    
     // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ($language->Icon) {
              
                   Storage::delete('public/language/' .  $language->Icon);
            }
            
     // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/language', $filename);
        $language->Icon = $filename;
        }
 
        $language->update([
       'Title' => $request->input('Title', $language->Title),
        'Icon' => $filename ?? $language->Icon,
          'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
           ]);
    \Log::info('After update', $language->toArray());

    // إعادة تحميل الموديل من قاعدة البيانات للتأكد
   $language->refresh();
    \Log::info('After refresh', $language->toArray());
    
     return response()->json([
        'message' => 'successfully',
        'data' => $language
    ]);
  
       /*  $language->update($validated);
        return response()->json($language); */
    }


    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Progrmming_language $proglanguage)
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
        /**
     * Show the form for editing the specified resource.
     */
    public function edit(Progrmming_language $proglanguage)
    {
        //
    }

}
