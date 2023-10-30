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
use Qcloud\Cos\Client;

class TencentCloud extends Platform
{
    /**
     * 驱动句柄
     * @var Client
     */
    protected $handler;

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // SecretId
        'secret_id' => '',
        // SecretKey
        'secret_key' => '',
        // 地域
        'region' => '',
        // 存储桶名称
        'bucket' => '',
        // 协议头部, http或者https
        'schema' => '',
    ];
    
    /**
     * 创建句柄
     * @access protected
     * @return $this
     */
    protected function makeHandler()
    {
        // 实例化客户端
        $this->handler = new Client([
            // 区域
			'region' => $this->options['region'],
            // 协议头部
			'schema' => $this->options['schema'],
            // 鉴权信息
			'credentials'=> [
				'secretId'  => $this->options['secret_id'],
				'secretKey' => $this->options['secret_key'],
			]
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

        try{
            // 响应
            $response = $this->handler->putObject([
                // 存储桶
                'Bucket' => $this->options['bucket'],
                // 文件路径
                'Key' => $key,
                // 文件内容
                'Body' => $body,
            ]);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }
        // 如果写入成功
        if(isset($response['Location']) && !empty($response['Location'])){
            // 返回成功
            return [[
                'md5' => $response['ETag'],
                'path' => '/' . $key,
            ], null];
        }
        // 返回错误信息
        return [null, new \Exception('上传失败')];
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
            $response = $this->handler->listObjects([
                // 存储桶
                'Bucket' => $this->options['bucket'],
                // 分隔符, 设置为/表示列出当前目录下的object, 设置为空表示列出所有的object
                'Delimiter' => '',
                // 编码格式
				'EncodingType' => 'url',
                // 起始对象键标记
				'Marker' => $marker,
                // 匹配指定前缀
				'Prefix' => ltrim($prefix, '/'),
                // 最大遍历出多少个对象, 一次listObjects最大支持1000
				'MaxKeys' => $maxKeys,
            ]);
        } catch (\Exception $e) {
            // 返回错误
            return [null, $e];
        }
        
        // 获取的object列表
        $list = [];
        // 如果对象为空
        if(isset($response['Contents']) && is_array($response['Contents'])){
            // 遍历获取的全部对象
            foreach ( $response['Contents'] as $content ) {
                $list[] = [
                    'key' => $content['Key'],
                    'lastModified' => strtotime($content['LastModified']),
                    'eTag' => $content['ETag'],
                    'size' => $content['Size'],
                ];
            }
        }

        // 要返回的数据
        $resultData = [
            'name' => $response['Name'],
            'prefix' => $response['Prefix'],
            'marker' => $response['Marker'],
            'maxKeys' => $response['MaxKeys'],
            'nextMarker' => $response['NextMarker'],
            'list' => $list,
        ];
        // 返回
        return [$resultData, null];
    }
}