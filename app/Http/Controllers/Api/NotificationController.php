<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function getNotification(){
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        $countNotView = Notification::where('is_read',0)->count();

        return $this->returnSuccess(200, ['notifications' => $notifications, 'countNotView' => $countNotView ]);
    }
    public function viewAllnotification(){
        Notification::where('is_read', 0)->update([
            'is_read' => 1
        ]);

        return $this->returnSuccess(200,'ok');
    }
}
