<?php

namespace App\Http\Controllers;

use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Videos\VideoRepositoryInterface;
use App\Services\GoogleDriveService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $userRepo;
    private $videoRepo;

    public function __construct(UserRepositoryInterface $userRepo, VideoRepositoryInterface $videoRepo)
    {
        $this->userRepo = $userRepo;
        $this->videoRepo = $videoRepo;
    }
    /**
     * Controller method render home view blade
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $user = $this->userRepo->getUserInfo(Auth::id());
        return view('home', [
            'user' => $user
        ]);
    }

    /**
     * Controller method upload video and push to Google Driver
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory\Iluminate\Routing\Redirector
     */
    public function uploadVideo(Request $request)
    {
        $param = $request->all();
        if (isset($param['video'])) {
            $file = $request->file('video');
            $fileName = md5(Carbon::now()) . '.mp4';
            $file->move(public_path('videos'), $fileName);
            $googleDriveService = new GoogleDriveService();
            try {
                $fileId = $googleDriveService->synchronize(public_path('videos/' . $fileName), $fileName);
                $videoData = [
                    'video_url' => 'https://drive.google.com/file/d/' . $fileId . '/preview',
                    'caption' => $param['caption'],
                    'author_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $this->videoRepo->create($videoData);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
        return redirect('/home');
    }
}
