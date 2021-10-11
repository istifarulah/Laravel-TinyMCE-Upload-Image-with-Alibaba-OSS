<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Ramsey\Uuid\Uuid;
use OSS\OssClient;
use OSS\Core\OssException;

class UploadController extends Controller
{
    public function upload()
    {
        reset($_FILES);
        $temp = current($_FILES);

        $ext        = pathinfo($temp['name'], PATHINFO_EXTENSION);
        $filename   = Uuid::uuid4().'.'.$ext;
        $file       = file_get_contents($temp['tmp_name']);

        $accessKeyId        = env('OSS_KEY');
        $accessKeySecret    = env('OSS_SECRET');
        $endpoint           = env('OSS_ENDPOINT');
        $bucket             = env('OSS_BUCKET');
        $ossClient          = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $ossClient->putObject($bucket, env('DIR_PROJECT').$filename, $file);
        $ossClient->putObjectAcl($bucket, env('DIR_PROJECT').$filename, 'public-read');

        $url = env('OSS_URL').env('DIR_PROJECT').$filename;

        $array = array(
            'location'  => $url
        );

        return response()->json($array, 200);
    }
}
