<?php
namespace App\Services;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService{
protected $messaging;
public function __construct()
{
  
$serviceAccountPath = storage_path('testing-5587a-firebase-adminsdk-csoea-3llbb88fb3.json');
$factory=(new Factory)->withServiceAccount($serviceAccountPath);
$this->CloudMessage::withTarget('token',$token)
->withNotification(['title'=>$title,'body'=>$body])
->withDate($date);
}



}
