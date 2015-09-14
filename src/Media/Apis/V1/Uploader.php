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
use FastD\Http\File\File;
use FastD\Http\Request;
use FastD\Http\Response;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;
use Media\Std\MediaInterface;

/**
 * Class Uploader
 *
 * @package Media\Apis\V1
 */
class Uploader extends RestEvent
{
    /**
     * @param Request $request
     * @param File    $file
     * @param null    $url
     * @return bool
     */
    protected function uploadRemote(Request $request, File $file, $url = null)
    {
        if (null === $url) {
            return false;
        }

        try {
            $prefix = $this->getParameters('media.remote.prefix');
        } catch (\Exception $e) {
            $prefix = '';
        }

        $data = array(
            'img'=> new \CURLFile($file->getPath() . '/' . $file->getFilename()),
            'prefix' => $prefix
        );
        $launcher = $request->createRequest($url, $data, 5);
        $response = $launcher->post();

        return json_decode($response->getContent())['url'];
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

    /**
     * @param MediaInterface|null $interface
     * @param File                $file
     * @param string              $thumb
     * @return bool
     */
    protected function createNewImageRecord(MediaInterface $interface = null, File $file, $thumb = '')
    {
        if (null === $interface) {
            return false;
        }

        if (false == ($row = $interface->find(['hash' => $file->getHash()]))) {
            return $interface->insert([
                $interface->getFieldOriginalName()=> $file->getOriginalName(),
                $interface->getFieldSaveName()    => $file->getFilename(),
                $interface->getFieldSavePath()    => $interface,
                $interface->getFieldThumbnilPath()=> $thumb,
                $interface->getFieldHash()        => $file->getHash(),
                $interface->getFieldSize()        => $file->getSize(),
                $interface->getFieldExt()         => $file->getExtension(),
            ]);
        }
        return $row['id'];
    }

    /**
     * @param Request $request
     * @return \FastD\Http\JsonResponse
     * @throws \Exception
     */
    public function uploadAction(Request $request)
    {
        if ($request->files->isEmpty()) {
            return $this->responseJson(['msg' => 'Empty files.'], Response::HTTP_BAD_REQUEST);
        }

        $uploadedFiles = $request->getUploader(
            [
                'path' => $this->getParameters('uploaded.path'),
                'exts' => $this->getParameters('uploaded.exts'),
                'size' => $this->getParameters('uploaded.size')
            ]
        )->uploading();

        if (null == ($files = $uploadedFiles->getUploadFiles())) {
            return $this->responseJson(['msg' => 'Uploaded files error.']);
        }

        try {
            $remote = $this->getParameters('media.remote.url');
        } catch (\Exception $e) {
            $remote = null;
        }

        try {
            $repository = $this->getParameters('media.repository');
            $connection = $this->getParameters('media.connection');
            $mediaRepository = $this->getConnection($connection)->getRepository($repository);
        } catch (\Exception $e) {
            $mediaRepository = null;
        }

        foreach ($files as $file) {
            $relativePath = str_replace(dirname($this->get('kernel')->getRootPath()) . DIRECTORY_SEPARATOR . 'public/', '', $file->getRealPath());
            $thumb = $this->toThumbnail(
                $relativePath,
                $this->getParameters('media.thumbnil.width'),
                $this->getParameters('media.thumbnil.height')
            );

            $id = $this->createNewImageRecord($mediaRepository, $file);
            $remoteUrl = $this->uploadRemote($request, $file, $remote);
        }

        return $this->responseJson([
            'id'            => $id,
            'url'           => $this->asset($relativePath, $this->getParameters('media.resrouces_url')),
            'thumb'         => $this->asset($thumb, $this->getParameters('media.resrouces_url')),
            'remote_url'    => $remoteUrl
        ]);
    }
}