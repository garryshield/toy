# CentosOS LAMP 自动化安装脚本

# 下载源码
```
cd /usr/src
git clone git@github.com:garryshield/toy.git
cd toy
chmod +x ./*
```

# 源码结构
.
├── README.md
├── cnf # 配置文件模板
│   ├── mariadb-10.3.8
│   ├── memcached-1.5.9
│   ├── nginx-1.14.0
│   ├── php-7.2.8
│   ├── pure-ftpd-1.0.47
│   ├── redis-4.0.11
│   ├── ssh
│   └── vhost
├── const # 常量，软件包名、安装目录、端口...
├── funs # 工具
├── init # 初始化脚本
├── mariadb # MariaDB 安装脚本
├── memcached # Memcahced 安装脚本
├── nginx # Nginx 安装脚本
├── nodejs # Node.js 安装脚本
├── php # Php 安装脚本
├── pure-ftpd # pure-ftpd 安装脚本
├── redis # Redis 安装脚本
├── ssh # SSH 初始化脚本
└── vhost # Nginx 虚拟主机脚本

Version              | Port
-                    | -
ssh                  | 2316
nginx-1.14.0         | 2317
mariadb-10.3.8       | 2318
pure-ftpd-1.0.47     | 2319
memcached-1.5.9      | 2320
redis-4.0.11         | 2321
php-7.2.8            | <none>     

程序安装目录
/usr/local/<包名>

配置安装目录：
/var/sites/<包名>

虚拟主机目录：
/vat/sites/htdocs/<SITE ID>
/vat/sites/htdocs/<SITE ID>/nginx-1.14.0 # 虚拟主机 nginx 配置
/vat/sites/htdocs/<SITE ID>/php-7.2.8 # 虚拟主机 php-fpm 配置
/vat/sites/htdocs/<SITE ID>/www # 虚拟主机根目录



