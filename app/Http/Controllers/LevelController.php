<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Resources\LevelTitleResource;
use Illuminate\Support\Facades\Gate; // لفحص الصلاحيات
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use App\Models\User;
class LevelController extends Controller
{
///////crad level by admain
    //show all level
   
    public function index(Request $request)
    {
       /*  if (!Auth::user()->Is_Admin) {
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

        $levels = Level::all();
       return response()->json([
        'data' => $levels,
        'current_language' => app()->getLocale()
    ]);
    }

//create new level 
    public function store(Request $request)
    {
         
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'RequiredXP' => 'required|integer',
            'Icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB كحد أقصى
            'title_translations' => 'sometimes|array',
            'title_translations.en' => 'required_with:title_translations|string',
            'title_translations.ar' => 'required_with:title_translations|string'
        ]);
            // معالجة وتخزين الصورة
             $filename = null;
          if ($request->hasFile('Icon')) {
        $icon = $request->file('Icon');
        $filename = uniqid() . str_replace(' ', '_', $icon->getClientOriginalName()); 
        $icon->storeAs('public/level', $filename);
    }

        // إنشاء المستوى الجديد
    $level = Level::create([
        'Title' => $validated['Title'],
        'RequiredXP' => $validated['RequiredXP'],
        'Icon' => $filename ? '' . $filename : null,
        'title_translations' => $validated['title_translations'] ?? [
            'en' => $validated['title_translations'],
            'ar' => $validated['title_translations'] // ترجمة افتراضية
        ],
    ]);

        return response()->json($level, 201);
    }

 //show spscific level  
    public function show(Request $request,$id)
    {
       /*  if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
          */ // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
         $locale = $request->header('Accept-Language', 'en');
         app()->setLocale($locale);
            $level = Level::findOrFail($id);
         return response()->json([
        'data' => $level,
        'current_language' => app()->getLocale()
    ]);
    }

   
//update specific level
    public function update(Request $request, $id)
    {
    \Log::info('Update request received', $request->all());
     if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
              }

    // العثور على المستوى المطلوب أو إرجاع 404
     $level = Level::findOrFail($id);
    \Log::info('Before update', $level->toArray());

    // التحقق من صحة البيانات
    $validated = $request->validate([
        'Title' => 'sometimes|string|max:100',
        'RequiredXP' => 'sometimes|integer',
        'Icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        'title_translations' => 'sometimes|array',
        'title_translations.en' => 'required_with:title_translations|string',
        'title_translations.ar' => 'required_with:title_translations|string',
        ]);
    
   
    // تحديث الحقول النصية
    if ($request->has('Title')) {
        $level->Title = $validated['Title'];
    }
    
    if ($request->has('RequiredXP')) {
        $level->RequiredXP = $validated['RequiredXP'];
    }
          
    if ($request->has('title_translations')) {
        $level->title_translations = $validated['title_translations'];
     }

    // معالجة الصورة إذا تم تقديمها
     if ($request->hasFile('Icon')) {
        // حذف الصورة القديمة إذا كانت موجودة
            if ($level->Icon) {
              
                   Storage::delete('public/level/' .  $level->Icon);
            }

        // حفظ الصورة الجديدة
        $icon = $request->file('Icon');
        $filename = uniqid().'_'.str_replace(' ', '_', $icon->getClientOriginalName());
        $icon->storeAs('public/level', $filename);
        $level->Icon = $filename;
        }
   // $level->save(); 
   $level->update([
       'Title' => $request->input('Title', $level->Title),
       'RequiredXP' => $request->input('RequiredXP', $level->RequiredXP),
        'Icon' => $filename ?? $level->Icon,
        'title_translations' => $request['title_translations'] ?? [
                'en' => $request['title_translations']['en'],
                'ar' => $request['title_translations']['ar']
            ],
           ]);
    \Log::info('After update', $level->toArray());
    // إعادة تحميل الموديل من قاعدة البيانات للتأكد
    $level->refresh();
    \Log::info('After refresh', $level->toArray());
    
     return response()->json([
        'message' => 'Level updated successfully',
        'data' => $level
    ]);
  
}

    
// Remove the specified level
    
/*    public function destroy($id) // استخدم $id بدلاً من Route Model Binding مؤقتاً
{
    if (!auth()->user()->Is_Admin) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $level = Level::findOrFail($id); // تأكد من وجود المستوى
    $level->delete();
    
    return response()->json([
        'message' => 'تم حذف المستوى بنجاح',
        'deleted_level' => $level
    ], 200);
} */
  
 
////////////////////////////////////////    


    public function create()
    {
        //
    }

 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        //
    }

}
