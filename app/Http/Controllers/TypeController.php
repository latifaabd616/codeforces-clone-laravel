<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TypeController extends Controller
{
    ///////crad type by admain
    

       // عرض جميع الأنواع
    public function index(Request $request)
    {
  /*       if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'غير مصرح'], 403);
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

        $type= Type::all();
          return response()->json([
        'data' => $type,
        'current_language' => app()->getLocale()
    ]);
    }

       // إنشاء نوع جديد
    public function store(Request $request)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'Criteria' => 'required|string',
            'Icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB كحد أقصى
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
            'criteria_translations' => 'sometimes|array',
            'criteria_translations.en' => 'required_with:criteria_translations|string',
            'criteria_translations.ar' => 'required_with:criteria_translations|string',
            
           // 'GrantDate' => 'required|date'
        ]);


           // معالجة وتخزين الصورة
             $filename = null;
          if ($request->hasFile('Icon')) {
        $icon = $request->file('Icon');
        $filename = uniqid() . str_replace(' ', '_', $icon->getClientOriginalName()); 
        $icon->storeAs('public/type', $filename);
    }
         // إنشاء المستوى الجديد
    $type = Type::create([
        'Title' => $validated['Title'],
        'Criteria' => $validated['Criteria'],
        'Icon' => $filename ? '' . $filename : null,
         'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['title_translations'],
            'ar' => $validated['title_translations'] // ترجمة افتراضية
        ],
        'criteria_translations' => $validated['criteria_translations'] ?? [
            'en' => $validated['criteria_translations'],
            'ar' => $validated['criteria_translations'] // ترجمة افتراضية
        ],
       // 'GrantDate' => $validated['GrantDate'],
        
    ]);

      return response()->json($type, 201);
    }


    // عرض نوع معين
    public function show(Request $request,$id)
    {
      /*   if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }
         */
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
        $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);
         $type = Type::findOrFail($id);
         return response()->json([
        'data' => $type,
        'current_language' => app()->getLocale()
    ]);
    }


    // تحديث نوع
    public function update(Request $request, $id)
    {  \Log::info('Update request received', $request->all());
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

    // العثور على المستوى المطلوب أو إرجاع 404
     $type = Type::findOrFail($id);
    \Log::info('Before update', $type->toArray());

      
        $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'Criteria' => 'required|string',
            'Icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB كحد أقصى
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string',
            'criteria_translations' => 'sometimes|array',
            'criteria_translations.en' => 'required_with:criteria_translations|string',
            'criteria_translations.ar' => 'required_with:criteria_translations|string',
            //'GrantDate' => 'required|date'
        ]);

        // تحديث الحقول النصية
    if ($request->has('Title')) {
        $type->Title = $validated['Title'];
    }
    
    if ($request->has('Criteria')) {
        $type->Criteria = $validated['Criteria'];
    }
     // تحديث الترجمات إذا تم تقديمها
     if ($request->has('title_translations')) {
          $type->title_translations = $validated['title_translations'];
     }
        
    if ($request->has('criteria_translations')) {
         $type->criteria_translations = $validated['criteria_translations'];
     }

  /*   if ($request->has('GrantDate')) {
        $type->GrantDate = $validated['GrantDate'];
    } */

     
    // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ($type->Icon) {
              
                   Storage::delete('public/type/' .  $type->Icon);
            }

            
        // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/type', $filename);
        $type->Icon = $filename;
        }

        
   // $level->save(); 
   $type->update([
       'Title' => $request->input('Title', $type->Title),
       'Criteria' => $request->input('Criteria', $type->Criteria),
        'Icon' => $filename ?? $type->Icon,
        //'GrantDate' => $request->input('GrantDate', $type->GrantDate),
        'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
        'criteria_translations' => $request['criteria_translations'] ?? [
                'en' => $request['criteria_translations']['en'],
                'ar' => $request['criteria_translations']['ar']
            ],
           ]);
    \Log::info('After update', $type->toArray());

    
    // إعادة تحميل الموديل من قاعدة البيانات للتأكد
    $type->refresh();
    \Log::info('After refresh', $type->toArray());
    
     return response()->json([
        'message' => 'Level updated successfully',
        'data' => $type
    ]);
/* 
        $type->update($validated);
        return response()->json($type); */
    }

    // حذف نوع
   /*  public function destroy($id)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

       $type = Type::findOrFail($id); // تأكد من وجود المستوى

        $type->delete();
        return response()->json(['message' => 'تم الحذف بنجاح'], 200);
    } */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

 
    public function edit(Type $type)
    {
        //
    }

   
   
}
