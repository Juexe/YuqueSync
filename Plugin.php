<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 语雀文档同步
 *
 * @package YuqueSync
 * @author Juexe
 * @version 1.0.0
 * @link http://juexe.cn
 */
class YuqueSync_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/write-post.php')->content = ['YuqueSync_Plugin', 'render'];
        Helper::addAction('yuque-sync', 'YuqueSync_Action');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     */
    public static function deactivate()
    {
        Helper::removeAction('yuque-sync');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $token     = new Typecho_Widget_Helper_Form_Element_Text('token', NULL, '', 'Token', '参考 https://www.yuque.com/yuque/developer/api#785a3731');
        $namespace = new Typecho_Widget_Helper_Form_Element_Text('namespace', NULL, '', 'Namespace', '参考 https://www.yuque.com/yuque/developer/api#21f2fa80');
        $form->addInput($token);
        $form->addInput($namespace);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    /**
     * 插件实现方法
     */
    public static function render($post)
    {
        //Typecho_Widget::widget('Widget_Options')->to($options);
        //$options->index('/action/yuque-sync?slug=');
        echo <<<EOT
        <script>
        // 同步语雀
        let more_desc = "\\n\\n<!--more-->\\n\\n";
        function yuque_sync() {
            let slug = $('#yuque_slug').val();
            if (slug.length === 0) {
                alert('slug 不能为空')
            };
            
            $.ajax({
                url: '/index.php/action/yuque-sync?slug=' + slug,
                success: function(res) {
                    if (res.status != null) {
                        alert('同步失败：' + res.message);
                    }else{
                        //console.log(res.data.body);
                        $('#slug').val(slug);
                        $('#title').val(res.data.title);
                        $('#text').val(res.data.custom_description + more_desc + res.data.body);
                    }
                }
            });
        }
        </script>
        
        <section id="custom-field" class="typecho-post-option">
            <label id="custom-field-expand" class="typecho-label">同步语雀</label>
            <br>
            Slug <input id="yuque_slug" type="text" value="{$post->slug}" placeholder="slug" style="150px">
            <button type="button" class="btn" onclick="yuque_sync()">获取</button>
            <span>（将会覆盖当前 slug、标题和文章内容）</span>
        </section>
EOT;
    }
}
