<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/9/6
 * Time: 下午7:25
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Media\Std;

interface MediaInterface
{
    public function getName();

    public function getPath();

    public function getThumbnil();

    public function getSize();

    public function getExtension();

    public function getImage($id);

    public function getFieldOriginalName();

    public function getFieldSaveName();

    public function getFieldSavePath();

    public function getFieldThumbnilPath();

    public function getFieldHash();

    public function getFieldSize();

    public function getFieldExt();

    public function getTags();

    public function getCategories();
}