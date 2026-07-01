<?php

namespace App\Http\Controllers;

use App\Models\User_preference;
use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use App\Models\ProgrammingLanguage;
use App\Models\Framework;
use App\Models\Platform;
use Illuminate\Support\Facades\Validator;


class UserPreferenceController extends Controller
{
     // إضافة تفضيلات التقنية لمشروع معين
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_project_id' => 'required|exists:user_projects,id',
            'programming_language_id' => 'nullable|exists:programming_languages,id',
            'framework_id' => 'nullable|exists:frameworks,id',
            'platform_id' => 'nullable|exists:platforms,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // التحقق من أن المشروع يخص المستخدم الحالي
        $userProject = UserProject::where('id', $request->user_project_id)
                                ->where('user_id', Auth::id())
                                ->firstOrFail();

        $preference = UserPreference::create([
            'userproject_id' => $request->user_project_id,
            'programminglanguage_id' => $request->programming_language_id,
            'framework_id' => $request->framework_id,
            'Platform_id' => $request->platform_id,
        ]);

        return response()->json([
            'message' => 'Preferences added successfully',
            'preference' => $preference,
        ], 201);
    }


  
    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Store a newly created resource in storage.
     */
  

    /**
     * Display the specified resource.
     */
 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User_preference $user_preference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User_preference $user_preference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User_preference $user_preference)
    {
        //
    }
}
