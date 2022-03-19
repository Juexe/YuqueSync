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
     * 获取文档列表
     */
    public function get_repo_docs()
    {
        $repo     = $this->request->filter('strip_tags', 'trim', 'xss')->repo;
        $username = $this->_config->username;
        $result   = $this->yuque_get("https://www.yuque.com/api/v2/repos/$username/$repo/docs");
        $this->response->throwJson($result);
    }

    /**
     * 获取仓库列表
     */
    public function get_repos()
    {
        $username = $this->_config->username;
        $result   = $this->yuque_get("https://www.yuque.com/api/v2/users/$username/repos");
        $this->response->throwJson($result);
    }

    /**
     * 获取文章详情
     */
    public function get_doc_details()
    {
        $namespace = $this->request->filter('strip_tags', 'trim', 'xss')->namespace;
        $slug      = $this->request->filter('strip_tags', 'trim', 'xss')->slug;
        $result    = $this->yuque_get("https://www.yuque.com/api/v2/repos/{$namespace}/docs/{$slug}?raw=1");
        $this->response->throwJson($result);
    }

    /**
     * 请求语雀接口
     */
    public function yuque_get($url)
    {
        $token = $this->_config->token;

        $client = Typecho_Http_Client::get();
        $client->setMethod('GET');
        $client->setHeader('User-Agent', 'Typecho-Yuque-Sync');
        $client->setHeader('X-Auth-Token', $token);
        $client->send($url);

        return json_decode($client->getResponseBody());
    }

    /**
     * 接口需要实现的入口函数
     *
     * @access public
     * @return void
     */
    public function action()
    {
        $do = $this->request->filter('strip_tags', 'trim', 'xss')->do;
        if (method_exists($this, $do)) {
            return $this->$do();
        }
    }
}