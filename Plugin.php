<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

/**
 * 语雀文档同步
 *
 * @package YuqueSync
 * @author Juexe
 * @version 1.2.0
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
        Typecho_Plugin::factory('admin/write-post.php')->bottom  = ['YuqueSync_Plugin', 'script'];
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
        $token    = new Typecho_Widget_Helper_Form_Element_Text('token', NULL, '', 'Token', '参考 https://www.yuque.com/yuque/developer/api#785a3731');
        $username = new Typecho_Widget_Helper_Form_Element_Text('username', NULL, '', 'Login（语雀用户名）', '参考 https://www.yuque.com/yuque/developer/api#21f2fa80');
        $form->addInput($token);
        $form->addInput($username);
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
     * 插件渲染
     * @param $post Widget_Contents_Post_Edit
     */
    public static function render($post)
    {
        $config = Typecho_Widget::widget('Widget_Options')->plugin('YuqueSync');
        $repos  = Typecho_Widget::widget('YuqueSync_Action')->get_repos();
        ?>
        <section id="custom-field" class="typecho-post-option yuque-sync-field">
            <label id="custom-field-expand" class="typecho-label">同步语雀</label>
            <?php if (empty($repos) || empty($repos['data'])): ?>
                <p><a href="/admin/options-plugin.php?config=YuqueSync" style="color: red">当前配置有误无法同步语雀，点击进入配置</a>（若无法进入配置页请<a href="/admin/plugins.php">重启插件</a>）</p>
            <?php endif; ?>
            <p>
                <label for="yuque_repo">知识库</label>
                <select name="" id="yuque_repo" onchange="repo_selected()">
                    <option value="">请选择</option>
                    <?php foreach ($repos['data'] as $repo): ?>
                        <option value="<?php echo $repo['slug'] ?>"><?php echo $repo['name'] ?></option>
                    <?php endforeach; ?>
                </select> &nbsp;
                <label for="yuque_slug">文档</label>
                <select name="" id="yuque_slug" onchange="update_yuque_link()">
                    <option value="">请选择</option>
                </select>
                <button type="button" class="btn" onclick="yuque_sync()">同步</button>
            </p>
            <p>对应语雀地址
                <a class="yuque-link" target="_blank" href=""></a>
            </p>
        </section>
        <?php
    }

    /**
     * 前端脚本
     * @param $post Widget_Contents_Post_Edit
     */
    public static function script($post)
    {
        ?>

        <script>
            function repo_selected() {
                let repo = $('#yuque_repo').val();
                let yuque_slug = $('#yuque_slug');
                yuque_slug.html('<option value="">请选择</option>');

                jQuery.ajax({
                    url: '/index.php/action/yuque-sync',
                    method: 'POST',
                    data: {
                        repo: repo,
                        do: 'get_repo_docs'
                    },
                    success: function (res) {
                        if (res.status != null) {
                            alert('获取文档列表失败：' + res.message);
                        } else {
                            res.data.forEach(function (e) {
                                yuque_slug.append(`<option value="${e.slug}">${e.title}</option>`)
                            })
                        }
                    }
                });
                update_yuque_link();
            }

            function yuque_sync() {
                let slug = $('#yuque_slug').val();
                let repo = $('#yuque_repo').val();

                if (slug.length === 0 || repo.length === 0) {
                    alert('请选择要同步的语雀知识库和文档');
                    return;
                }

                $.ajax({
                    url: '/index.php/action/yuque-sync',
                    method: 'POST',
                    data: {
                        repo: repo,
                        slug: slug,
                        do: 'get_doc_details'
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
                let namespace = $('#yuque_repo').val();
                let slug = $('#yuque_slug').val();
                let link = `https://www.yuque.com/${namespace}/${slug}`;
                $('.yuque-link').attr('href', link).html(link);
            }

        </script>

        <?php
    }
}
