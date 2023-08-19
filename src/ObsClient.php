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

namespace think;

use think\helper\Arr;
use think\exception\InvalidArgumentException;

/**
 * OBS客户端
 */
class ObsClient extends Manager
{
    /**
     * 驱动的命名空间
     * @var string
     */
	protected $namespace = '\\think\\obsclient\\driver\\';

	/**
     * 默认驱动
     * @access public
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }

	/**
     * 获取客户端配置
     * @access public
     * @param null|string $name 配置名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->app->config->get('obsclient.' . $name, $default);
        }

        return $this->app->config->get('obsclient');
    }

	/**
     * 获取平台配置
     * @param string $platform 平台名称
     * @param null|string $name 配置名称
     * @param null|string $default 默认值
     * @return array
     */
    public function getPlatformConfig(string $platform, string $name = null, $default = null)
    {
		// 读取驱动配置文件
        if ($config = $this->getConfig('platforms.' . $platform)) {
            return Arr::get($config, $name, $default);
        }
		// 驱动不存在
        throw new \InvalidArgumentException('平台 [' . $platform . '] 配置不存在.');
    }

    /**
     * 当前平台的驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveType(string $name)
    {
        return $this->getPlatformConfig($name, 'type', 'tencent');
    }

	/**
     * 获取驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveConfig(string $name)
    {
        return $this->getPlatformConfig($name);
    }

	/**
     * 选择或者切换平台
     * @access public
     * @param string $name 平台的配置名
     * @param array $options 平台配置
     * @return \think\ocpcclient\Platform
     */
    public function platform(string $name = null, array $options = [])
    {
        return $this->driver($name)->setConfig($options);
    }
}
