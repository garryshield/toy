# CentosOS LAMP 自动化安装脚本

# 下载源码
```
cd /usr/src
git clone git@github.com:garryshield/toy.git
cd toy
```

# 源码结构
```
.
├── README.md
├── cnf # 配置文件模板
│   ├── mariadb-10.3.13
│   ├── memcached-1.5.12
│   ├── nginx-1.14.2
│   ├── php-7.2.16
│   ├── php-5.6.40
│   ├── php-5.4.45
│   ├── pure-ftpd-1.0.48
│   ├── redis-5.0.4
│   ├── elasticsearch-6.7.0
│   └── vhost
├── const # 常量，软件包名、安装目录、端口...
├── init # 初始化脚本
├── mariadb # MariaDB 安装脚本
├── memcached # Memcahced 安装脚本
├── nginx # Nginx 安装脚本
├── nodejs # nvm 安装脚本
├── python # pyenv && pipenv 安装脚本
├── php # Php 安装脚本
├── pure-ftpd # pure-ftpd 安装脚本
├── redis # Redis 安装脚本
├── elasticsearch # Elasticsearch 安装脚本
├── ssh # SSH 初始化脚本
└── vhost # 虚拟主机脚本
```

# 安装目录
程序安装目录：
```
/usr/local/<包名>
```

# ssh
```
./ssh install [port]
```

# nginx
```
./nginx install <version>
./nginx group -v=<version> -p=<prefix> -n=<name> -u=<user> -g=<group>
./nginx server -v=<version> -p=<prefix> -n=<name> -u=<user> -g=<group>
```

# mariadb
```
./mariadb install <version>
./mariadb group -v=<version> -p=<prefix>
./mariadb server -v=<version> -p=<prefix> -n=<name> -P=<port> -u=<user> -g=<group> --password=<password>
```

# pure-ftpd
```
./pure-ftpd install <version>
./pure-ftpd group -v=<version> -p=<prefix>
./pure-ftpd server -v=<version> -p=<prefix> -P=<port>
```

# memcached
```
./memcached install <version>
./memcached group -v=<version> -p=<prefix>
./memcached server -v=<version> -p=<prefix> -P=<port> -u=<user> -g=<group>
```

# redis
```
./redis install <version>
./redis group -v=<version> -p=<prefix>
./redis server -v=<version> -p=<prefix> -P=<port>
```

# elasticsearch
```
./elasticsearch install <version>
./elasticsearch group -v=<version> -p=<prefix>
./elasticsearch server -v=<version> -p=<prefix> -P=<port> -u=<user> -g=<group>
```

# vhost
默认端口

| Version               | Port                |
| -                     | -                   |
| ssh                   | 22                  |
| nginx                 | 80                  |
| mariadb               | 3306                |
| pure-ftpd             | 21  3000-5000       |
| memcached             | 11211               |
| redis                 | 6379                |
| elasticsearch         | 9200                |


配置安装目录：
```
/var/sites/<包名>
```

虚拟主机目录：
```
/vat/sites/<vhost_id>
/vat/sites/<vhost_id>/nginx-<version> # 虚拟主机 nginx 配置
/vat/sites/<vhost_id>/php-<version> # 虚拟主机 php-fpm 配置
...
/vat/sites/<vhost_id>/www # 虚拟主机根目录
```

# 安装顺序
```
./init
./ssh # 注：SSH 禁用了密码登录并自动生成 Key，安装后重启前请手动下载 Key 到本地

./nginx
./mariadb
./php
./pure-ftpd
./memcached
./redis
./elasticsearch
```

# 管理服务
```
systemctl [status|start|stop|restart] <包名>
```

# 下载 SSH Key
```
scp -r -P <port> <username>@<host>:/root/.ssh/ <dist>
```

# nginx vhost 结构
/var/sites/<vhost_id>/nginx-<version>

```
.
├── data
│   └── default
├── etc
│   └── default # default group
│       ├── conf.d
│       │   └── www.conf  # www server
│       ├── env
│       ├── nginx.conf
│       └── segment
│           ├── fastcgi.conf
│           ├── fastcgi_params
│           ├── koi-utf
│           ├── koi-win
│           ├── mime.types
│           ├── scgi_params
│           ├── uwsgi_params
│           └── win-utf
├── log
│   └── default
│       ├── nginx.log
│       ├── www.access.log
│       └── www.error.log
├── systemd
│   └── nginx-1.14.2@cf8d2679be150e4506801333b8e3353c.service
└── var
    └── default
        ├── client_body_temp
        ├── fastcgi_temp
        ├── nginx.pid
        ├── proxy_temp
        ├── scgi_temp
        └── uwsgi_temp

16 directories, 16 files
```

# php vhost 结构
/var/sites/<vhost_id>/php-<version>

```
.
├── data
│   └── default
├── etc
│   └── default # default group
│       ├── env
│       ├── php-fpm.conf
│       ├── php-fpm.d
│       │   └── www.conf # www pool
│       ├── php-ini.d
│       │   ├── ext.ini
│       │   └── php.ini -> /var/sites/www_test_com/php-7.2.16/etc/default/php.ini-development
│       ├── php.ini-development
│       └── php.ini-production
├── log
│   └── default
│       ├── php-fpm.log
│       ├── www.access.log
│       ├── www.error.log
│       └── www.slow.log
├── systemd
│   └── php-7.2.16@3528c30fb17061bf8aed0a24407b4b61.service
└── var
    └── default
        ├── php-fpm.pid
        └── www.socket

11 directories, 14 files
```

# mariadb vhost 结构
/var/sites/<vhost_id>/mariadb-<version>

```
.
├── data
│   └── default # default server data
│       ├── aria_log.00000001
│       ├── aria_log_control
│       ├── ib_buffer_pool
│       ├── ibdata1
│       ├── ib_logfile0
│       ├── ib_logfile1
│       ├── ibtmp1
│       ├── mariadb-bin.000001
│       ├── mariadb-bin.000002
│       ├── mariadb-bin.index
│       ├── mariadb-error.log
│       ├── mariadb-general.log
│       ├── mariadb-slow.log
│       ├── multi-master.info
│       ├── mysql
│       └── performance_schema
├── etc
│   └── default # default server configure
│       ├── my.cnf
│       └── my.cnf.d
│           ├── client.cnf
│           ├── mysql-clients.cnf
│           └── server.cnf
├── log
│   └── default
├── systemd
│   └── mariadb-10.3.13@8f12d7c1424315b8fe98aa3dbe12e922.service
└── var
    └── default
        ├── mariadb.pid
        └── mariadb.socket

12 directories, 18 files
```
