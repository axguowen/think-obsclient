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
use BaiduBce\BceClientConfigOptions;
use BaiduBce\Services\Bos\BosClient;

class BaiduBce extends Platform
{
    /**
     * 驱动句柄
     * @var BosClient
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // AccessKey
        'access_key' => '',
        // SecretKey
        'secret_key' => '',
        // 地域
        'endpoint' => '',
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
        // 实例化要请求产品的 client 对象
        $this->handler = new BosClient([
            BceClientConfigOptions::CREDENTIALS => [
                'accessKeyId' => $this->options['access_key'],
                'secretAccessKey' => $this->options['secret_key'],
            ],
            BceClientConfigOptions::ENDPOINT => $this->options['endpoint'],
        ]);
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
        // 处理key
        $key = trim($key, '/');
        // 获取内容长度
        $contentLength = strlen($body);
        // 获取内容MD5值
        $contentMd5 = base64_encode(md5($body, true));
        try{
            $response = $this->handler->putObject($this->options['bucket'], $key, $body, $contentLength, $contentMd5);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }
        // 操作成功
        if($response->statuscode == 200){
            // 返回成功
            return [[
                'md5' => $response->metadata['etag'],
                'path' => '/' . $key,
            ], null];
        }
        // 返回错误
        return [null, new \Exception($response->message)];
    }
}