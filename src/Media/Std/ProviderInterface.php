<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/9/15
 * Time: 上午10:57
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Media\Std;

interface ProviderInterface
{
    public function getMedia($page, $offset, $limited, $lastId);
}