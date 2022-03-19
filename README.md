# YuqueSync

本插件可以在 [Typecho][6] 编辑器中直接同步[语雀][1]文档内容。

该功能使用语雀官方接口实现，更多说明见 [开发者文档][3]。

## 使用方式

### 1 安装插件

克隆本仓库到 Typecho 插件目录下，然后在后台启用即可。

```
cd /usr/plugins/
git clone https://github.com/Juexe/YuqueSync.git
```

### 2 配置

需要配置 `token` 和 `namespace` 两个参数，具体含义详见 [用户认证][4] 和 [参数说明][5]。

配置界面：

![配置](./config.png)

### 3 同步

现在打开编辑文章页面，已经可以看到编辑器底部多了一块 `同步语雀` 的区块，
填写语雀文档 [Slug][5] 即可同步。

效果预览：

![效果预览](https://i.vgy.me/cYYi31.gif)

（完）

[1]: https://www.yuque.com/yuque/help/about "语雀是什么"
[2]: https://www.yuque.com/yuque/help "语雀用户手册"
[3]: https://www.yuque.com/yuque/developer "语雀开发者文档"
[4]: https://www.yuque.com/yuque/developer/api#785a3731 "语雀开发者文档#用户认证"
[5]: https://www.yuque.com/yuque/developer/api#21f2fa80 "语雀开发者文档#参数说明"
[6]: https://github.com/typecho/typecho	"Typecho 源码"
[7]: http://typecho.org/about "关于 Typecho"