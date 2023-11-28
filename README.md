# YuqueSync

> 注：语雀目前使用 API 同步需要超级会员 :(

- 本插件可以在 [Typecho][6] 编辑器中直接同步[语雀][1]文档内容
- 本插件基于语雀官方接口实现，数据不经过任何第三方

- V1.2 开始支持下拉选择知识库和文档，如从旧版本升级后需重新启用插件

## 使用方式

### 1 启用

克隆本仓库到 Typecho 插件目录下，然后在后台启用即可。

```
cd typecho/usr/plugins/
git clone https://github.com/Juexe/YuqueSync.git
# 如需更新版本执行 git pull 即可
```

### 2 配置

请到`插件管理`中配置语雀 `token` 和 `login` 两个参数。（未来可能升级为只需 token 即可）

### 3 同步

现在打开编辑文章页面，已经可以看到编辑器底部多了一块 `同步语雀` 的区块，
选择知识库和文档后点击同步按钮即可。

效果预览：

![image](https://github.com/Juexe/YuqueSync/assets/26461438/a0c1bbc5-298d-4a8c-ba4c-f9a12b60dd27)

（完）

[1]: https://www.yuque.com/yuque/help/about "语雀是什么"
[2]: https://www.yuque.com/yuque/help "语雀用户手册"
[3]: https://www.yuque.com/yuque/developer "语雀开发者文档"
[4]: https://www.yuque.com/yuque/developer/api#785a3731 "语雀开发者文档#用户认证"
[5]: https://www.yuque.com/yuque/developer/api#21f2fa80 "语雀开发者文档#参数说明"
[6]: https://github.com/typecho/typecho	"Typecho 源码"
[7]: http://typecho.org/about "关于 Typecho"
