<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/25
 * Time: 下午11:59
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Media\Repository;

use FastD\Database\Pagination\QueryPagination;
use FastD\Database\Repository\Repository;
use Media\Std\MediaInterface;
use Media\Std\ProviderInterface;

class MediaRepository extends Repository implements MediaInterface,ProviderInterface
{
    protected $media;

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getPath()
    {
        // TODO: Implement getPath() method.
    }

    public function getThumbnil()
    {
        // TODO: Implement getThumbnil() method.
    }

    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    public function getExtension()
    {
        // TODO: Implement getExtension() method.
    }

    public function getImage($id)
    {
        $this->media = $this->find(['id' => $id]);

        return $this->media;
    }

    public function getFieldOriginalName()
    {
        return 'name';
    }

    public function getFieldSaveName()
    {
        return 'save_name';
    }

    public function getFieldSavePath()
    {
        return 'path';
    }

    public function getFieldThumbnilPath()
    {
        return 'thumb';
    }

    public function getFieldHash()
    {
        return 'hash';
    }

    public function getFieldSize()
    {
        return 'size';
    }

    public function getFieldExt()
    {
        return 'ext';
    }

    public function getTags()
    {
        // TODO: Implement getTags() method.
    }

    public function getCategories()
    {
        // TODO: Implement getCategories() method.
    }

    public function getMedia($page, $offset, $limited, $lastId)
    {
        return [];
    }
}