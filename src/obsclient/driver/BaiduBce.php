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
use BaiduBce\Services\Bos\BosOptions;

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
     * @param string $contentType
     * @return array
     */
    public function putObject(string $key, $body, $contentType = '')
    {
        // 处理key
        $key = trim($key, '/');
        // 获取内容长度
        $contentLength = strlen($body);
        // 获取内容MD5值
        $contentMd5 = base64_encode(md5($body, true));
        // 其它选项
        $options = [];
        // 指定了类型
        if(!empty($contentType)){
            $options[BosOptions::CONTENT_TYPE] = $contentType;
        }
        try{
            $response = $this->handler->putObject($this->options['bucket'], $key, $body, $contentLength, $contentMd5, $options);
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

    /**
     * 列出对象
     * @access public
     * @param string $prefix
     * @param int $maxKeys
     * @param string $marker
     * @return array
     */
    public function listObjects(string $prefix, int $maxKeys = 10, string $marker = '')
    {
        try{
            // 响应
            $response = $this->handler->listObjects($this->options['bucket'], [
                // 分隔符, 设置为/表示列出当前目录下的object, 设置为空表示列出所有的object
                BosOptions::DELIMITER => '',
                // 起始对象键标记
				BosOptions::MARKER => $marker,
                // 匹配指定前缀
				BosOptions::PREFIX => ltrim($prefix, '/'),
                // 最大遍历出多少个对象, 一次listObjects最大支持1000
                BosOptions::MAX_KEYS => $maxKeys,
            ]);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }

        // 操作失败
        if($response->statuscode != 200){
            // 返回成功
            return [null, new \Exception($response->message)];
        }
        
        // 获取的object列表
        $list = [];
        // 遍历获取的全部对象
		foreach ( $response->contents as $content ) {
			$list[] = [
				'key' => $content->key,
                'lastModified' => strtotime($content->lastModified),
                'eTag' => $content->eTag,
                'size' => $content->size,
			];
		}

        // 要返回的数据
        $resultData = [
            'name' => $response->name,
            'prefix' => $response->prefix,
            'marker' => $marker,
            'maxKeys' => $response->maxKeys,
            'nextMarker' => $response->marker,
            'list' => $list,
        ];
        // 返回
        return [$resultData, null];
    }

    /**
     * 删除对象
     * @access public
     * @param string $key
     * @return array
     */
    public function deleteObject(string $key)
    {
        // 处理key
        $key = trim($key, '/');
        try{
            $response = $this->handler->deleteObject($this->options['bucket'], ['key' => $key]);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }
        // 操作成功
        if($response->statuscode == 200){
            // 返回成功
            return ['操作成功', null];
        }
        // 返回错误
        return [null, new \Exception($response->message)];
    }

    /**
     * 批量删除对象
     * @access public
     * @param array $objects
     * @return array
     */
    public function deleteObjects(array $objects)
    {
        // 要删除的对象
        $objectsForDelete = [];
        // 遍历对象
        foreach($objects as $object){
            $objectsForDelete[] = [
                'key' => trim($object['key'], '/'),
            ];
        }

        try{
            $response = $this->handler->deleteMultipleObjects($this->options['bucket'], $objectsForDelete);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }
        // 操作成功
        if($response->statuscode == 200){
            // 返回成功
            return ['操作成功', null];
        }
        // 返回错误
        return [null, new \Exception($response->message)];
    }
}