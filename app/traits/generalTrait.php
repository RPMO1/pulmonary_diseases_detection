<?php

namespace App\traits;

trait generalTrait
{
    public function uploadAudio($audio_path, $folder)
    {
        $path = time() . '.' . $audio_path->extension();
        $audio_path->move(public_path('audios\\' . $folder), $path);
        return $path;
    }

    public function returnSuccessMessage($message = "", $statusCode = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'errors' => (object)[],
                'data' => (object)[],
                // 'statusCode' => $statusCode
            ],
            $statusCode
        );
    }

    public function returnErrorMessage($message = "", $statusCode = 404)
    {
        return response()->json(
            [
                'message' => $message,
                'errors' => (object)[],
                'data' => (object)[],
                // 'statusCode' => $statusCode
            ],
            $statusCode
        );
    }

    public function returnData($data, $message = "", $statusCode = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'errors' => (object)[],
                'data' => $data,
                // 'statusCode' => $statusCode
            ],
            $statusCode
        );
    }
}
