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

namespace think\obsclient\driver;

use think\obsclient\Platform;
use axguowen\ctyun\services\oos\Auth;
use axguowen\ctyun\services\oos\OosClient;

class Ctyun extends Platform
{
    /**
     * 驱动句柄
     * @var OosClient
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // API接入所使用的KeyId
        'key_id' => '',
        // API接入所使用的密钥
        'key_secret' => '',
        // 存储桶名称
        'bucket' => '',
    ];
    
    /**
     * 创建句柄
     * @access protected
     * @return $this
     */
    protected function makeHandler()
    {
        /*/ 实例化授权对象, SDK开发中
        $credential = new Auth($this->options['key_id'], $this->options['key_secret']);
        // 实例化要请求产品的 client 对象
        $this->handler = new OosClient($credential);
        //*/
        // 返回
        return $this;
    }

    /**
     * 上传一个文件
     * @access public
     * @param string $key
     * @param mixed $body
     * @return array
     */
    public function putObject(string $key, $body)
    {
        // 返回错误
        return [null, new \Exception('暂不支持该功能')];
    }
}