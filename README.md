# CentosOS LAMP 自动化安装脚本

# 下载源码
```
cd /usr/src
git clone git@github.com:garryshield/toy.git
cd toy
chmod +x ./*
```

# 源码结构
```
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

9 directories, 13 files
```

# 默认端口
| Version              | Port       |
| -                    | -          |
| ssh                  | 2316       |
| nginx-1.14.0         | 2317       |
| mariadb-10.3.8       | 2318       |
| pure-ftpd-1.0.47     | 2319       |
| memcached-1.5.9      | 2320       |
| redis-4.0.11         | 2321       |

# 安装目录
程序安装目录：
```
/usr/local/<包名>
```

配置安装目录：
```
/var/sites/<包名>
```

虚拟主机目录：
```
/vat/sites/htdocs/<SITE ID>
/vat/sites/htdocs/<SITE ID>/nginx-1.14.0 # 虚拟主机 nginx 配置
/vat/sites/htdocs/<SITE ID>/php-7.2.8 # 虚拟主机 php-fpm 配置
/vat/sites/htdocs/<SITE ID>/www # 虚拟主机根目录
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
```

# 管理服务
```
systemctl [status|start|stop|restart] <包名>
```

# 下载 SSH Key
```
scp -r -P <port> <username>@<host>:/root/.ssh/ <dist>
```

# php 结构
/var/sites/<PHP_VERSION>
e.g. PHP_VERSION = php-5.4.45
```
tree /var/sites/php-5.4.45
.
├── data
├── etc
│   ├── env
│   ├── group   # pool 组，通过 systemctl {start|stop|reload|status} php-5.4.45@group 管理
│   │   ├── env   # 组环境变量用于 php-5.4.45@.service 中的 EnvironmentFile
│   │   ├── php-fpm.conf
│   │   ├── php-fpm.d
│   │   │   └── www.conf  # pool 配置文件
│   │   └── php.ini.d
│   │       └── www.ini   # 当前组下所有 pool 共用的 php.ini 配置，若具体的 pool 需要单独的配置,可在 <pool.conf> 中修改
                          # 通过组下的 env 文件中的 PHP_INI_SCAN_DIR 找到
                          # 注：php-5.4.45 版本中 PHP_INI_SCAN_DIR 不支持追加和指定多个查找目录
│   ├── php-fpm.conf
│   ├── php-fpm.d
│   │   └── www.conf
│   ├── php.ini -> /var/sites/php-5.4.45/etc/php.ini-development
│   ├── php.ini.d
│   │   └── www.ini
│   ├── php.ini-development
│   └── php.ini-production
├── log
│   ├── group.log   # group 组的 php-fpm 日志
│   ├── group.www.access.log    # group 组中 www pool 的 access 日志
│   ├── group.www.error.log
│   ├── group.www.slow.log
│   ├── php-fpm.log
│   ├── php-fpm.www.access.log
│   ├── php-fpm.www.error.log
│   └── php-fpm.www.slow.log
├── systemd
│   ├── php-5.4.45.service
│   └── php-5.4.45@.service   # systemd unit template 用来管理组
└── var
    ├── group.pid
    ├── group.www.socket    # group 组中 www pool 的 socket
    ├── php-fpm.pid
    └── php-fpm.www.socket
```

TODO:
1 把组名从 pool 配置文件中的提取到 ExecStartPre