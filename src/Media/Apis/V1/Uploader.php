<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/25
 * Time: 下午10:51
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Media\Apis\V1;

use FastD\Framework\Events\RestEvent;
use FastD\Http\Request;
use FastD\Http\Response;

class Uploader extends RestEvent
{
    public function uploadAction(Request $request)
    {
        if ($request->files->isEmpty()) {
            return $this->responseJson(['msg' => 'Empty files.'], Response::HTTP_BAD_REQUEST);
        }

        $uploadedFiles = $request->getUploader(
            [
                'save.path' => $this->getParameters('uploaded.path'),
                'allow.ext' => $this->getParameters('uploaded.exts'),
                'max.size' => $this->getParameters('uploaded.size')
            ]
        )->uploading();

        if (null == ($files = $uploadedFiles->getUploadFiles())) {
            return $this->responseJson(['msg' => 'Uploaded files error.']);
        }

        $write = $this->getConnection('media');

        $id = 0;

        foreach ($files as $file) {
            $relativePath = str_replace(dirname($this->get('kernel')->getRootPath()) . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            if (false == ($row = $write->find('fastd_media', ['hash' => $file->getHash()]))) {
                $id = $write->insert('fastd_media', [
                    'name' => $file->getOriginalName(),
                    'save_name' => $file->getFilename(),
                    'path' => $relativePath,
                    'hash' => $file->getHash(),
                    'size' => $file->getSize(),
                    'ext' => $file->getExtension(),
                    'create_at' => time(),
                    'update_at' => time(),
                ]);
            } else {
                $id = $row['id'];
            }
        }

        return $this->responseJson([
            'msg' => 'successful.',
            'file' => [
                'id' => $id,
                'path' => $this->asset($relativePath),
            ]
        ]);
    }
}