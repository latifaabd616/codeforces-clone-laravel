<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\UserProjectController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\ProjectController ;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\ProgrammingLanguageController;
use App\Http\Controllers\ProjectTechnologyController;
use App\Http\Controllers\XpCategoryController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectReviewController ;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RankingController;





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

///////////authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
 
//Route::apiResource('users', UserController::class);
Route::apiResource('levels', LevelController::class); 

////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->group(function () {
Route::apiResource('users', UserController::class);

//authentication
Route::post('/logout', [AuthController::class, 'logout']);

/////////////////////////

//show user with level and type and badges "profile"
Route::get('users/{id}', [UserController::class, 'show']);
//show all project are accepted for user
Route::get('users/{user}/accepted-projects', [UserProjectController::class, 'getAcceptedProjects']);
//show projects selected by the user
Route::get('/users/{userId}/projects', [UserProjectController::class, 'getUserProjects']);

////////////////////////

//update username ,avatar,Biograogy
Route::get('/profile', [UserProfileController::class, 'show']);
Route::post('/profile', [UserProfileController::class, 'update']);
//change password
Route::post('/change-password', [PasswordController::class, 'changePassword']);

//delete user
 Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');

 ///////////////////////

 //show all information badges
 Route::get('/badges', [BadgeController::class, 'index']);
 //get a specific user with badges and the number of badges,"badges recived by the user"
 Route::get('/users/{userId}/badges', [UserBadgeController::class, 'getUserBadgesWithCount']);

 ////////////////////////
 

 //choosing a new project and inserting project_specific preferences by the user  
 Route::post('/projects', [ProjectController::class, 'store']);
//inserting project_specific preferences by the user     
// Route::post('/preferences', [UserPreferenceController::class, 'store']);


///////////////////////////
// send project link
    Route::post('/user/projects/submit', [UserProjectController::class, 'submitProjectFile']);

//////////////////////////  
//making modification in level table by the admain"crad" 

Route::get('levels', [LevelController::class, 'index']);
Route::post('levels', [LevelController::class, 'store']);
Route::get('levels/{level}', [LevelController::class, 'show']);
Route::post('levels/{level}', [LevelController::class, 'update']);


//////////////////////////
//making modification in type table by the admain"crad" 


Route::get('types', [TypeController::class, 'index']);
Route::post('types', [TypeController::class, 'store']);
Route::get('types/{type}', [TypeController::class, 'show']);
Route::post('types/{type}', [TypeController::class, 'update']);

///////////////////////////
//making modification in badge table by the admain"crad" 

Route::post('badges', [BadgeController::class, 'store']);

Route::post('badges/{badge}', [BadgeController::class, 'update']);

///////////////////////////
//making modification in userbadge table by the admain"crad" 
/* Route::get('/user-badges', [UserBadgeController::class, 'index']);
Route::post('/user-badges', [UserBadgeController::class, 'store']);
Route::get('/user-badges/{id}', [UserBadgeController::class, 'show']);
Route::post('/user-badges/{id}', [UserBadgeController::class, 'update']); */

//////////////////////////
//making modification in framework table by the admain"crad" 
Route::get('frameworks', [FrameworkController::class, 'index']);
Route::post('frameworks', [FrameworkController::class, 'store']);
Route::get('frameworks/{framework}', [FrameworkController::class, 'show']);
Route::post('frameworks/{framework}', [FrameworkController::class, 'update']);

///////////////////////
//making modification in programminglanguage table by the admain"crad" 
Route::get('programmings', [ProgrammingLanguageController::class, 'index']);
Route::post('programmings', [ProgrammingLanguageController::class, 'store']);
Route::get('programmings/{programming}', [ProgrammingLanguageController::class, 'show']);
Route::post('programmings/{programming}', [ProgrammingLanguageController::class, 'update']);

///////////////////////
//making modification in platform table by the admain"crad" 
Route::get('platforms', [PlatformController::class, 'index']);
Route::post('platforms', [PlatformController::class, 'store']);
Route::get('platforms/{platform}', [PlatformController::class, 'show']);
Route::post('platforms/{platform}', [PlatformController::class, 'update']);

/////////////////////////
//making modification in project table by the admain"crad " 
//Route::get('projectsadmin', [ProjectController::class, 'indexproject']);
Route::post('projectsadmin', [ProjectController::class, 'storeproject']);
//Route::get('projectsadmin/{project}', [ProjectController::class, 'showproject']);
Route::post('projectsadmin/{project}', [ProjectController::class, 'updateproject']);

///////////////////////
//making modification in project table by the admain"crad" 
Route::get('projecttechnologies', [ProjectTechnologyController::class, 'index']);
//Route::post('projecttechnologies', [ProjectTechnologyController::class, 'store']);
Route::get('projecttechnologies/{projecttechnology}', [ProjectTechnologyController::class, 'show']);
//Route::post('projecttechnologies/{projecttechnology}', [ProjectTechnologyController::class, 'update']);

///////////////////////
//making modification in xpcategory table by the admain"crad" 
Route::get('xpcategories', [XpCategoryController::class, 'index']);
Route::post('xpcategories', [XpCategoryController::class, 'store']);
Route::get('xpcategories/{xpcategory}', [XpCategoryController::class, 'show']);
Route::post('xpcategories/{xpcategory}', [XpCategoryController::class, 'update']);

//////////////////////
//make the user admin
 Route::post('/users/{id}/make-admin', [UserController::class, 'makeAdmin']);
 //جلب اكل المستخدمين الذين ليسوا مسوولين
 Route::get('/userss', [UserController::class, 'index']);
 // make the admin user
  Route::post('/users/{id}/make-user', [UserController::class, 'makeuser']);

 /////////////////////
 //اختيار المشروع المفضل
 Route::post('/projects/{project}/favorite', [UserProjectController::class, 'toggleFavorite']);

 /////////////////////
/*  //ادخال البيانات على جدول الجلسة
 Route::post('/sessions', [SessionController::class, 'store']);
 //تعديل البيانات على جدول الجلسة
Route::post('/sessions/{id}', [SessionController::class, 'update']); */
 // جلسات المشاريع
Route::post('/sessions', [SessionController::class, 'store']);
//اظهار الجلسات الخاصة بالمشرع
//Route::get('/user/projects/{projectId}/sessions', [SessionController::class, 'getUserProjectSessions']);
Route::get('/users/{userId}/projects/{projectId}/sessions', [SessionController::class, 'getUserSessionsForProject']);

//////////////////////
 // المشاريع التي تحتاج إلى مراجعة
Route::get('/admin/pending-project-reviews', [ProjectReviewController::class, 'pendingReviews']);
 // عرض حل مشروع معين
Route::get('/projects/review/{id}', [ProjectReviewController::class, 'showReview']);
 // تحديث حالة المراجعة
 Route::post('/projects/review/{id}', [ProjectReviewController::class, 'updateReview']);
 ///////////////////////////////////
Route::post('/send-notification',[NotificationController::class,'sendPushNotification']);

///////////////////////////////////
//جلب اول 10اوائل
Route::get('/ranking/top', [RankingController::class, 'topUsers']);
//عرض rankلمستخدم معين
Route::get('/ranking/user/{userId}', [RankingController::class, 'userRank']);

///////////////////////////////////////////////
// إنشاء إشعار (للمسؤولين فقط)
Route::post('/notifications', [NotificationController::class, 'store']);

 // الحصول على الإشعارات  المقروءة وغير المقروءة لمستخدم معين (للمسؤولين)
Route::get('/users/{userId}/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
 //الحصول على عدد الإشعارات غير المقروءة لمستخدم معين
Route::get('/users/{userId}/notificationscount/unread', [NotificationController::class, 'getUnreadNotificationscount']);
//الحصول على اشعار معين
Route::get('notifications/{id}', [NotificationController::class, 'showAndMarkAsRead']);
// مسار لتحديث حالة القراءة فقط
Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);


///////////////////////////////////////////
//get all admin
Route::get('/getadmins', [UserController::class, 'getAdmins']);
//get specific admin
Route::get('/getadmins/{id}', [UserController::class, 'getAdmin']);

/////////////////////////////
//show category details for a specific user"s project
Route::get('user-projects/{userProjectId}', [ProjectController::class, 'getUserProjectDetails']);



});






//جلب اول 10اوائل
Route::get('/ranking/top', [RankingController::class, 'topUsers']);

///////////////////////
//making modification in project table by the admain"crad" 
Route::get('projecttechnologies', [ProjectTechnologyController::class, 'index']);
//Route::post('projecttechnologies', [ProjectTechnologyController::class, 'store']);
Route::get('projecttechnologies/{projecttechnology}', [ProjectTechnologyController::class, 'show']);
//Route::post('projecttechnologies/{projecttechnology}', [ProjectTechnologyController::class, 'update']);


////////////////////////////////
//show all information badges
 Route::get('/badges', [BadgeController::class, 'index']);
Route::get('badges', [BadgeController::class, 'index']);
Route::get('badges/{badge}', [BadgeController::class, 'show']);
//////////////////////////
//reset password
Route::post('/request-password-reset', [PasswordController::class, 'requestReset']);
Route::post('/resetPassword', [PasswordController::class, 'resetPassword']);

//////////////
//Route::get('user-projects/{userProjectId}', [ProjectController::class, 'getUserProjectDetails']);

/////////////////////
 //get all project with  their available technologies
 Route::get('projects', [ProjectController::class, 'index']);
 //get a specific project with its available technologies
Route::get('projects/{id}', [ProjectController::class, 'show']);
