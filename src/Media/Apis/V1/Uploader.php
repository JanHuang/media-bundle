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

use FastD\Debug\Exceptions\FatalError;
use FastD\Framework\Events\RestEvent;
use FastD\Http\Request;
use FastD\Http\Response;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;
use Media\Std\MediaInterface;

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

        $mediaRepository = $this->getConnection($this->getParameters('media.connection'))->getRepository($this->getParameters('media.repository'));

        if (!($mediaRepository instanceof MediaInterface)) {
            throw new FatalError('Media repository must be extends Media\Std\MediaInterface');
        }

        $id = 0;

        foreach ($files as $file) {
            $relativePath = str_replace(dirname($this->get('kernel')->getRootPath()) . DIRECTORY_SEPARATOR . 'public/', '', $file->getRealPath());
            $thumb = $this->toThumbnail(
                $relativePath,
                $this->getParameters('media.thumbnil.width'),
                $this->getParameters('media.thumbnil.height')
            );

            if (false == ($row = $mediaRepository->find(['hash' => $file->getHash()]))) {
                $id = $mediaRepository->insert([
                    $mediaRepository->getFieldOriginalName()=> $file->getOriginalName(),
                    $mediaRepository->getFieldSaveName()    => $file->getFilename(),
                    $mediaRepository->getFieldSavePath()    => $relativePath,
                    $mediaRepository->getFieldThumbnilPath()=> $thumb,
                    $mediaRepository->getFieldHash()        => $file->getHash(),
                    $mediaRepository->getFieldSize()        => $file->getSize(),
                    $mediaRepository->getFieldExt()         => $file->getExtension(),
                ]);
            } else {
                $id = $row['id'];
            }
        }

        return $this->responseJson([
            'id' => $id,
            'url' => $this->asset($relativePath, $this->getParameters('media.resrouces_url')),
            'thumb' => $this->asset($thumb, $this->getParameters('media.resrouces_url')),
        ]);
    }

    /**
     * @param     $img
     * @param int $width
     * @param int $height
     * @return \Imagine\Image\ManipulatorInterface
     */
    public function toThumbnail($img, $width = 120, $height = 120)
    {
        $thumbnail = pathinfo($img, PATHINFO_DIRNAME) . '/' . pathinfo($img, PATHINFO_FILENAME) . '_thumbnil.' . pathinfo($img, PATHINFO_EXTENSION);
        $imagine = new Imagine();
        $imagine->open($img)
            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($thumbnail)
        ;
        return $thumbnail;
    }
}