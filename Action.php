<?php
/**
 * 同步语雀文档操作类
 *
 * @package YuqueSync
 * @author Juexe
 * @version 1.0.0
 */
class YuqueSync_Action extends Typecho_Widget implements Widget_Interface_Do
{
    /**
     * 插件配置
     *
     * @access private
     * @var Typecho_Config
     */
    private $_config;

    /**
     * 构造方法
     */
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        /* 获取插件配置 */
        $this->_config = parent::widget('Widget_Options')->plugin('YuqueSync');
    }

    /**
     * 返回同步结果
     */
    public function sync()
    {
        $namespace = $this->request->filter('strip_tags', 'trim', 'xss')->namespace;
        $slug = $this->request->filter('strip_tags', 'trim', 'xss')->slug;
        $token = $this->_config->token;

        $client = Typecho_Http_Client::get();
        $client->setMethod('GET');
        $client->setHeader('User-Agent', 'Typecho-Yuque-Sync');
        $client->setHeader('X-Auth-Token', $token);
        $client->send("https://www.yuque.com/api/v2/repos/{$namespace}/docs/{$slug}?raw=1");

        $result = json_decode($client->getResponseBody());
        $this->response->throwJson($result);
    }

    /**
     * 接口需要实现的入口函数
     *
     * @access public
     * @return void
     */
    public function action()
    {
        $this->on($this->request->isAjax())->sync();
    }
}