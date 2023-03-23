# data-dictionary
PHP生成MySQL数据库字典，可以翻译英文表名、字段、备注为中文


## 使用步骤

1. 下载代码本地或者服务器的PHP容器中，修改 `index.php` 文件里 `app_init()`将其中定义的如下常量换成你的

```php
define('MYSQL_HOST','localhost');
define('MYSQL_PORT','3306');
define('MYSQL_DB','mysql');
define('MYSQL_USER','root');
define('MYSQL_PASS','root');
```

2. 在代码目录下执行 `php -S localhost:8000` 开启web服务器,php版本建议5.5+

3. 浏览器里输入[http://localhost:8000/](http://localhost:8000/) 生成`crawRecords.dat`文件

4. 在代码目录下执行`php crawRecords.php`自动进行翻译

5. 再次刷新浏览器 即可

> 目录里提供了一个 Demo: [mysql-Data-Dictionary.html](/china-data-dictionary/mysql-Data-Dictionary.html)

6. 如果对自动翻译的结果不满意,也可访问[http://localhost:8000/?q=trans](http://localhost:8000/?q=trans)修改

7. 修改完成后,再次访问 [http://localhost:8000/](http://localhost:8000/) 即可

 

## 其他

来源项目：[https://gitee.com/amlove2/China-Data-Dictionary.git](https://gitee.com/amlove2/China-Data-Dictionary.git)

对原项目进行bug修复和优化。
