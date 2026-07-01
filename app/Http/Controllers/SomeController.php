<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use App\Models\User;

class SomeController extends Controller
{
    
public function someAction($id)
{
    // بعض المنطق هنا...
    
    $user = User::find($id);
    $user->notify(new UserNotification('تم تنفيذ الإجراء بنجاح!'));
    
    return redirect()->back()->with('success', 'تمت العملية بنجاح');
}
}
