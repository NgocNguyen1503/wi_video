<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseApi;
use App\Models\Follow;
use App\Models\User;
use App\Models\Video;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Videos\VideoRepositoryInterface;
use App\Services\GoogleDriveService;
use App\Services\GoogleHangoutWebhook;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleSpreadSheetService;

class HomeController extends Controller
{
    private $userRepo;
    private $videoRepo;
    private $responseAPI;

    public function __construct(UserRepositoryInterface $userRepo, VideoRepositoryInterface $videoRepo)
    {
        $this->userRepo = $userRepo;
        $this->videoRepo = $videoRepo;
        $this->responseAPI = new ResponseApi();
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

    public function getVideo(Request $request)
    {
        $param = $request->all();
        $userId = Auth::user()->id;
        $video = $this->videoRepo->getVideo($param['video_id']);
        $isLike = false;
        $myVideo = false;
        $follow = false;
        if (count($video->likes) > 0) {
            foreach ($video->likes as $like) {
                if ($like->user_id == $userId) {
                    $isLike = true;
                }
            }
        }
        if ($video->author_id == $userId) {
            $myVideo = true;
        } else {
            $follow = $this->userRepo->find($userId)->followers->pluck('follow_id')->toArray();
            if (in_array($userId, $follow)) {
                $follow = true;
            }
        }
        $video->is_like = $isLike;
        $video->my_video = $myVideo;
        $video->follow = $follow;
        return $this->responseAPI->success($video);
    }

    public function sendReport(Request $request)
    {
        $param = $request->all();

        // $googleHangout = new GoogleHangoutWebhook();
        // $googleHangout->reportForWebHook(
        //     $param['report_content'],
        //     Auth::user()->name,
        // );

        $video = Video::find($param['video_id']);

        // Insert report to Google Spreadsheets
        $googleSpreadsheet = new GoogleSpreadSheetService();
        $data = [
            'values' => [
                (string) $video->id,
                $video->caption,
                Auth::user()->name . ' bao cao ' . $param['report_content'],
                $video->video_url,
                (string) Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        try {
            $googleSpreadsheet->writeSheet('Trang tính1', $data);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return $this->responseAPI->success();
    }
}
