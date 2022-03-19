<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

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
        $namespace = new Typecho_Widget_Helper_Form_Element_Text('namespace', NULL, '', 'Namespace（默认）', '该值为缺省值，同步时会使用文章页面中填写的值。参考 https://www.yuque.com/yuque/developer/api#21f2fa80');
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
     * @param $post Widget_Contents_Post_Edit
     */
    public static function render($post)
    {
        //Typecho_Widget::widget('Widget_Options')->to($options);
        //$options->index('/action/yuque-sync?slug=');
        $config  = Typecho_Widget::widget('Widget_Options')->plugin('YuqueSync');
        $yq_link = "https://www.yuque.com/{$config->namespace}/{$post->slug}";
        ?>
        <section id="custom-field" class="typecho-post-option yuque-sync-field">
            <label id="custom-field-expand" class="typecho-label">同步语雀</label>
            <p>
                <label for="yuque_namespace">Namespace</label>
                <input id="yuque_namespace" type="text" value="<?php echo $config->namespace; ?>"
                       placeholder="namespace" style="150px" oninput="update_yuque_link()">

                <label for="yuque_slug">Slug</label>
                <input id="yuque_slug" type="text" value="<?php echo $post->slug; ?>" placeholder="slug" style="150px" oninput="update_yuque_link()">
                <button type="button" class="btn" onclick="yuque_sync()">同步</button>
            </p>
            <p>对应语雀地址
                <a class="yuque-link" target="_blank" href="<?php echo $yq_link ?>"><?php echo $yq_link ?></a>
            </p>
        </section>

        <script>
            function yuque_sync() {
                let slug = $('#yuque_slug').val();
                let namespace = $('#yuque_namespace').val();

                if (slug.length === 0 || namespace.length === 0) {
                    alert('namespace 和 slug 不能为空');
                    return;
                }

                if (!confirm(`即将请求语雀知识库，并覆盖当前文章！`)) {
                    return;
                }

                $.ajax({
                    url: '/index.php/action/yuque-sync',
                    method: 'POST',
                    data: {
                        namespace: namespace,
                        slug: slug
                    },
                    success: function (res) {
                        if (res.status != null) {
                            alert('同步失败：' + res.message);
                        } else {
                            //console.log(res.data.body);
                            $('#slug').val(slug);
                            $('#title').val(res.data.title);
                            let desc = res.data.custom_description ? res.data.custom_description + "\n\n<!--more-->\n\n" : '';
                            $('#text').val(desc + res.data.body);
                        }
                    }
                });
            }

            function update_yuque_link() {
                let namespace = $('#yuque_namespace').val();
                let slug = $('#yuque_slug').val();
                let link = `https://www.yuque.com/${namespace}/${slug}`;
                $('.yuque-link').attr('href', link).html(link);
            }
        </script>

        <?php
    }
}
