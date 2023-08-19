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
     * @param string $body
     * @return array
     */
    public function putObject(string $key, string $body);
}
