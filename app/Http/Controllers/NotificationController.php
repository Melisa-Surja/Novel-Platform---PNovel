<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SEOTools::setTitle("Notifications");

        $user = auth()->user();

        [$notifications_pagination, $notifications] = $user->getNotifications();

        $links = $notifications_pagination->links();

        return view('frontend.notifications', compact('notifications_pagination', 'notifications', 'links'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'read'   => 'required|boolean'
        ]);

        $notification->update($data);

        // Remove Cache
        $user = auth()->user();
        cache()->tags(['notification', "user:$user->id:notification"])
            ->forget("user:$user->id:notification:$notification->id");
        cache()->tags("notification")->forget("user:$user->id:notification:unread");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = $request->validate([
            'method'   => 'required|string'
        ]);

        $user = auth()->user();

        // Remove Cache
        cache()->tags("notification")->forget("user:$user->id:notification:unread");

        if ($request->method == "all") {
            // Remove Cache
            cache()->tags("user:$user->id:notification")->flush();

            $user->notifications()->delete();
            return redirect()->back()->with('status', 'Successfully deleted all notifications.');
        } else {
            // delete that specific notification
            $n = Notification::findOrFail($id);

            // Remove Cache
            cache()->tags(['notification', "user:$user->id:notification"])
                ->forget("user:$user->id:notification:$n->id");

            $n->delete();
        }

        return redirect()->back()->with('status', 'Successfully deleted the notification.');
    }
}
