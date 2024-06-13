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

return [
    // 默认平台
    'default' => 'tencent',
    // 平台配置
    'platforms' => [
        // 腾讯云
        'tencent' => [
            // 驱动类型
            'type'          => 'TencentCloud',
            // SecretId
            'secret_id'     => '',
            // SecretKey
            'secret_key'    => '',
            // 存储桶所属地域
            'region'        => '',
            // 存储桶名称
            'bucket'        => '',
            // 协议头部, http或者https
			'schema'        => '',
        ],
        // 百度云
        'baidu' => [
            // 驱动类型
            'type'          => 'BaiduBce',
            // AccessKey
            'access_key'    => '',
            // SecretKey
            'secret_key'    => '',
            // 存储桶所属地域, https://cloud.baidu.com/doc/BOS/s/Wjwvys2yd
            'endpoint'      => '',
            // 存储桶名称
            'bucket'        => '',
        ],
        // 天翼云
        'ctyun' => [
            // 驱动类型
            'type'          => 'Ctyun',
            // API接入所使用的KeyId
            'key_id'        => '',
            // API接入所使用的密钥
            'key_secret'    => '',
            // 存储桶名称
            'bucket'        => '',
        ],
        // 其它
        'other' => [
            // 驱动类型
            'type'          => 'TencentCloud',
            // SecretId
            'secret_id'     => '',
            // SecretKey
            'secret_key'    => '',
            // 存储桶所属地域
            'region'        => '',
            // 存储桶名称
            'bucket'        => '',
        ],
    ]
];
