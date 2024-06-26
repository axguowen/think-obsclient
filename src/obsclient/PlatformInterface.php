<?php
// +----------------------------------------------------------------------
// | ThinkPHP OBS Client [Simple Object Storage Client For ThinkPHP]
// +----------------------------------------------------------------------
// | ThinkPHP OBS 客户端
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace think\obsclient;

/**
 * Platform interface
 */
interface PlatformInterface
{
    /**
     * 上传一个文件
     * @access public
     * @param string $key
     * @param mixed $body
     * @param string $contentType
     * @return array
     */
    public function putObject(string $key, $body, $contentType = '');

    /**
     * 列出对象
     * @access public
     * @param string $prefix
     * @param int $maxKeys
     * @param string $marker
     * @return array
     */
    public function listObjects(string $prefix, int $maxKeys = 10, string $marker = '');

    /**
     * 删除对象
     * @access public
     * @param string $key
     * @return array
     */
    public function deleteObject(string $key);

    /**
     * 批量删除对象
     * @access public
     * @param array $objects
     * @return array
     */
    public function deleteObjects(array $objects);
}
