##  快速部署

1. 请使用`git`下载源码包到服务器环境的根目录下

    ```bash
    git clone https://github.com/Tinywan/go.git
    ```
2. 使用`composer`自动安装依赖

    ```bash
    composer install 
    ```
3. 将根目录下的`resty.sql`文件导入到自己的`MySQL`数据库

4. 修改数据库配置文件`config/office.php`,修改为链接自己的数据库

5. 将你的域名（或者IP）指向根目录下的public目录

6. 浏览器访问：你的域名/console 默认管理员账户：resty 密码：resty_test

7. 如果你用到了短信配置，请前往阿里大鱼官网申请下载自己的sdk文件，替换/extend/dayu下的文件，在后台配置自己的appkey即可