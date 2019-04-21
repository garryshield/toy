#!/usr/bin/env bash

# Name: php
# Desc: an auto web server installation tool
# Date: 2013-02-07 by garryshield

source ./const

help() {
    echo "Usage: $0 {install|group|server|help} [option...]" >&2
    echo
    echo "    install <version>"
    echo "    group -v=<version> -p=<prefix> -n=<name>"
    echo "    server -v=<version> -p=<prefix> -n=<name> -s=<server> -u=<user> -g=<group>"
    echo
    # echo some stuff here for the -a or --add-options 
    exit 1
}

# php install <version>
# <version> e.g. 7.2.16
# php install 7.2.16
install() {
  if [ "$#" -ne 1 ]; then
    help
    exit 1
  fi

  local Php=php-$1
  local PhpPackName=php-$1.tar.gz
  # local PhpUrlPath=https://www.php.net/distributions/${PhpPackName}
  local PhpUrlPath=http://192.168.113.1:8080/${PhpPackName}
  local PhpSrcPath=${ToySrcPath}/${Php}
  local PhpTarPath=${ToyTmpPath}/${Php}
  local PhpPackPath=${ToySrcPath}/${Php}/${PhpPackName}
  local PhpInstallPath=${ToySoftPath}/${Php}

  echo "${Php} installation"
  echo "pleace input <Enter> twice to continue or <CTRL+C> to exit:"
  read
  read

  # backup
  if [ -d "${PhpInstallPath}" ]; then
    mv ${PhpInstallPath} ${PhpInstallPath}-`date '+%Y-%m-%d(%H:%M:%S)'`
  fi

  rm -rf ${PhpSrcPath}
  rm -rf ${PhpTarPath}

  mkdir -p ${PhpSrcPath}
  mkdir -p ${PhpTarPath}
  
  wget ${PhpUrlPath} -O ${PhpPackPath}

  local retVal=$?
  if [ "${retVal}" -ne 0 ]; then
      exit ${retVal}
  fi

  tar -zxvf ${PhpPackPath} -C ${PhpTarPath}
  cd ${PhpTarPath}/${Php}

  # configure
  ./configure --help > ../configure

  case "$1" in
    7.2.16)
      local PhpConfigure=(
        --prefix=${PhpInstallPath}

        --with-libdir=lib64
        
        --enable-cgi
        --enable-cli

        --enable-fpm
        # --with-fpm-user=${PhpUser}
        # --with-fpm-group=${PhpGroup}
        --with-fpm-systemd
        --with-fpm-acl
        --with-litespeed
        --enable-phpdbg
        --enable-phpdbg-webhelper
        --enable-phpdbg-debug

        --enable-debug
        # --with-config-file-path=${PhpEtcPath}
        # --with-config-file-scan-dir=${PhpEtcPath}/php-ini.d
        # --enable-sigchild
        --enable-libgcc
        --enable-dtrace
        --enable-fd-setsize=2048

        # libxml
        # https://www.php.net/manual/en/book.libxml.php
        --enable-libxml
        --with-libxml-dir

        # openssl
        # https://www.php.net/manual/en/book.openssl.php
        --with-openssl
        --with-system-ciphers

        # pcre
        # https://www.php.net/manual/en/book.pcre.php
        --with-pcre-dir
        --with-pcre-regex
        --with-pcre-jit

        # zlib
        # https://www.php.net/manual/en/book.zlib.php
        --with-zlib

        # bc
        # https://www.php.net/manual/en/book.bc.php
        --enable-bcmath

        # bzip2
        # https://www.php.net/manual/en/book.bzip2.php
        --with-bz2

        # calendar
        # https://www.php.net/manual/en/book.calendar.php
        --enable-calendar

        # curl
        # https://www.php.net/manual/en/book.curl.php
        --with-curl

        # dba
        # https://www.php.net/manual/en/book.dba.php

        # dom
        # https://www.php.net/manual/en/book.dom.php
        --enable-dom

        # enchant
        # https://www.php.net/manual/en/book.enchant.php
        --with-enchant

        # exif
        # https://www.php.net/manual/en/book.exif.php
        --enable-exif

        # ftp
        # https://www.php.net/manual/en/book.ftp.php
        --enable-ftp
        
        # gd
        # https://www.php.net/manual/en/book.image.php
        --with-gd
        --with-webp-dir
        --with-jpeg-dir
        --with-png-dir
        --with-xpm-dir
        --with-freetype-dir
        --enable-gd-jis-conv

        # gettext
        # https://www.php.net/manual/en/book.gettext.php
        --with-gettext

        # gmp
        # https://www.php.net/manual/en/book.gmp.php
        --with-gmp

        # mhash
        # https://www.php.net/manual/en/book.mhash.php
        --with-mhash

        # iconv
        # https://www.php.net/manual/en/book.iconv.php
        --with-iconv

        # imap
        # https://www.php.net/manual/en/book.imap.php
        # --with-imap
        # --with-imap-ssl

        # intl
        # https://www.php.net/manual/en/book.intl.php
        --enable-intl

        # mbstring
        # https://www.php.net/manual/en/book.mbstring.php
        --enable-mbstring
        --with-libmbfl
        --with-onig

        # opcache
        # https://www.php.net/manual/en/book.opcache.php
        --enable-opcache

        # pcntl
        # https://www.php.net/manual/en/book.pcntl.php
        --enable-pcntl

        # phar
        # https://www.php.net/manual/en/book.phar.php
        --enable-phar

        # readline
        # https://www.php.net/manual/en/book.readline.php
        --with-libedit
        --with-readline
        
        # recode
        # https://www.php.net/manual/en/book.recode.php
        --with-recode

        # shmop
        # https://www.php.net/manual/en/book.shmop.php
        --enable-shmop

        # snmp
        # https://www.php.net/manual/en/book.snmp.php
        --with-snmp

        # soap
        # https://www.php.net/manual/en/book.soap.php
        --enable-soap

        # sockets
        # https://www.php.net/manual/en/book.sockets.php
        --enable-sockets

        # sodium
        # https://www.php.net/manual/en/book.sodium.php
        --with-sodium

        # password
        # https://www.php.net/manual/en/book.password.php
        --with-password-argon2

        # sem
        # https://www.php.net/manual/en/book.sem.php
        --enable-sysvmsg
        --enable-sysvsem
        --enable-sysvshm

        # tidy
        # https://www.php.net/manual/en/book.tidy.php
        --with-tidy

        # tokenizer
        # https://www.php.net/manual/en/book.tokenizer.php
        --enable-tokenizer

        # wddx
        # https://www.php.net/manual/en/book.wddx.php
        --enable-wddx

        # xmlrpc
        # https://www.php.net/manual/en/book.xmlrpc.php
        --with-xmlrpc

        # xsl
        # https://www.php.net/manual/en/book.xsl.php
        --with-xsl

        # zip
        # https://www.php.net/manual/en/book.zip.php
        --enable-zip
        --with-libzip

        # pear
        --with-pear

        # mysql
        # https://www.php.net/manual/en/set.mysqlinfo.php
        --enable-mysqlnd
        # --with-mysql
        --with-mysqli=mysqlnd
        --with-pdo-mysql=mysqlnd
        # --with-mysql-sock=${HostSitesPath}/${MariaDB}/var/default/mariadb.socket
      )
      local Imagick=imagick-3.4.3
      local Redis=redis-4.3.0
      local Memcached=memcached-3.1.3
      local Swoole=swoole-4.3.3
      ;;
    5.6.40)
      local PhpConfigure=(
        --prefix=${PhpInstallPath}

        --with-libdir=lib64

        --enable-cgi
        --enable-cli

        --enable-fpm
        # --with-fpm-user=${PhpUser}
        # --with-fpm-group=${PhpGroup}
        --with-fpm-systemd
        --with-fpm-acl
        --with-litespeed
        --enable-phpdbg
        # --enable-phpdbg-webhelper
        --enable-phpdbg-debug

        --enable-debug
        # --with-config-file-path=${PhpEtcPath}
        # --with-config-file-scan-dir=${PhpEtcPath}/php-ini.d
        # --enable-sigchild
        --enable-libgcc
        --enable-dtrace
        --enable-fd-setsize=2048

        # libxml
        # https://www.php.net/manual/en/book.libxml.php
        --enable-libxml
        --with-libxml-dir

        # openssl
        # https://www.php.net/manual/en/book.openssl.php
        --with-openssl
        --with-system-ciphers

        # pcre
        # https://www.php.net/manual/en/book.pcre.php
        --with-pcre-dir
        --with-pcre-regex
        # --with-pcre-jit

        # zlib
        # https://www.php.net/manual/en/book.zlib.php
        --with-zlib

        # bc
        # https://www.php.net/manual/en/book.bc.php
        --enable-bcmath

        # bzip2
        # https://www.php.net/manual/en/book.bzip2.php
        --with-bz2

        # calendar
        # https://www.php.net/manual/en/book.calendar.php
        --enable-calendar

        # curl
        # https://www.php.net/manual/en/book.curl.php
        --with-curl

        # dba
        # https://www.php.net/manual/en/book.dba.php

        # dom
        # https://www.php.net/manual/en/book.dom.php
        --enable-dom

        # enchant
        # https://www.php.net/manual/en/book.enchant.php
        --with-enchant

        # exif
        # https://www.php.net/manual/en/book.exif.php
        --enable-exif

        # ftp
        # https://www.php.net/manual/en/book.ftp.php
        --enable-ftp
        
        # gd
        # https://www.php.net/manual/en/book.image.php
        --with-gd
        # --with-webp-dir
        --with-jpeg-dir
        --with-png-dir
        --with-xpm-dir
        --with-freetype-dir
        --enable-gd-jis-conv

        # gettext
        # https://www.php.net/manual/en/book.gettext.php
        --with-gettext

        # gmp
        # https://www.php.net/manual/en/book.gmp.php
        --with-gmp

        # mhash
        # https://www.php.net/manual/en/book.mhash.php
        --with-mhash

        # iconv
        # https://www.php.net/manual/en/book.iconv.php
        --with-iconv

        # imap
        # https://www.php.net/manual/en/book.imap.php
        # --with-imap
        # --with-imap-ssl

        # intl
        # https://www.php.net/manual/en/book.intl.php
        --enable-intl

        # mbstring
        # https://www.php.net/manual/en/book.mbstring.php
        --enable-mbstring
        --with-libmbfl
        --with-onig

        # opcache
        # https://www.php.net/manual/en/book.opcache.php
        --enable-opcache

        # pcntl
        # https://www.php.net/manual/en/book.pcntl.php
        --enable-pcntl

        # phar
        # https://www.php.net/manual/en/book.phar.php
        --enable-phar

        # readline
        # https://www.php.net/manual/en/book.readline.php
        --with-libedit
        --with-readline
        
        # recode
        # https://www.php.net/manual/en/book.recode.php
        --with-recode

        # shmop
        # https://www.php.net/manual/en/book.shmop.php
        --enable-shmop

        # snmp
        # https://www.php.net/manual/en/book.snmp.php
        --with-snmp

        # soap
        # https://www.php.net/manual/en/book.soap.php
        --enable-soap

        # sockets
        # https://www.php.net/manual/en/book.sockets.php
        --enable-sockets

        # sodium
        # https://www.php.net/manual/en/book.sodium.php
        # --with-sodium

        # password
        # https://www.php.net/manual/en/book.password.php
        # --with-password-argon2

        # sem
        # https://www.php.net/manual/en/book.sem.php
        --enable-sysvmsg
        --enable-sysvsem
        --enable-sysvshm

        # tidy
        # https://www.php.net/manual/en/book.tidy.php
        --with-tidy

        # tokenizer
        # https://www.php.net/manual/en/book.tokenizer.php
        --enable-tokenizer

        # wddx
        # https://www.php.net/manual/en/book.wddx.php
        --enable-wddx

        # xmlrpc
        # https://www.php.net/manual/en/book.xmlrpc.php
        --with-xmlrpc

        # xsl
        # https://www.php.net/manual/en/book.xsl.php
        --with-xsl

        # zip
        # https://www.php.net/manual/en/book.zip.php
        --enable-zip
        --with-libzip

        # pear
        --with-pear

        # mysql
        # https://www.php.net/manual/en/set.mysqlinfo.php
        --enable-mysqlnd
        --with-mysql
        --with-mysqli=mysqlnd
        --with-pdo-mysql=mysqlnd
        # --with-mysql-sock=${HostSitesPath}/${MariaDB}/var/default/mariadb.socket
      )
      local Imagick=imagick-3.4.3
      local Redis=redis-4.3.0
      local Memcached=memcached-2.2.0
      local Swoole=swoole-2.0.12
      ;;
    5.4.45)
      local PhpConfigure=(
        --prefix=${PhpInstallPath}

        --with-libdir=lib64

        --enable-cgi
        --enable-cli

        --enable-fpm
        # --with-fpm-user=${PhpUser}
        # --with-fpm-group=${PhpGroup}
        --with-fpm-systemd
        # --with-fpm-acl
        # --with-litespeed
        # --enable-phpdbg
        # --enable-phpdbg-webhelper
        # --enable-phpdbg-debug

        --enable-debug
        # --with-config-file-path=${PhpEtcPath}
        # --with-config-file-scan-dir=${PhpEtcPath}/php-ini.d
        # --enable-sigchild
        --enable-libgcc
        --enable-dtrace
        --enable-fd-setsize=2048

        # libxml
        # https://www.php.net/manual/en/book.libxml.php
        --enable-libxml
        --with-libxml-dir

        # openssl
        # https://www.php.net/manual/en/book.openssl.php
        --with-openssl
        # --with-system-ciphers

        # pcre
        # https://www.php.net/manual/en/book.pcre.php
        --with-pcre-dir
        --with-pcre-regex
        # --with-pcre-jit

        # zlib
        # https://www.php.net/manual/en/book.zlib.php
        --with-zlib

        # bc
        # https://www.php.net/manual/en/book.bc.php
        --enable-bcmath

        # bzip2
        # https://www.php.net/manual/en/book.bzip2.php
        --with-bz2

        # calendar
        # https://www.php.net/manual/en/book.calendar.php
        --enable-calendar

        # curl
        # https://www.php.net/manual/en/book.curl.php
        --with-curl

        # dba
        # https://www.php.net/manual/en/book.dba.php

        # dom
        # https://www.php.net/manual/en/book.dom.php
        --enable-dom

        # enchant
        # https://www.php.net/manual/en/book.enchant.php
        --with-enchant

        # exif
        # https://www.php.net/manual/en/book.exif.php
        --enable-exif

        # ftp
        # https://www.php.net/manual/en/book.ftp.php
        --enable-ftp
        
        # gd
        # https://www.php.net/manual/en/book.image.php
        --with-gd
        # --with-webp-dir
        --with-jpeg-dir
        --with-png-dir
        --with-xpm-dir
        --with-freetype-dir
        --enable-gd-jis-conv

        # gettext
        # https://www.php.net/manual/en/book.gettext.php
        --with-gettext

        # gmp
        # https://www.php.net/manual/en/book.gmp.php
        --with-gmp

        # mhash
        # https://www.php.net/manual/en/book.mhash.php
        --with-mhash

        # iconv
        # https://www.php.net/manual/en/book.iconv.php
        --with-iconv

        # imap
        # https://www.php.net/manual/en/book.imap.php
        # --with-imap
        # --with-imap-ssl

        # intl
        # https://www.php.net/manual/en/book.intl.php
        --enable-intl

        # mbstring
        # https://www.php.net/manual/en/book.mbstring.php
        --enable-mbstring
        --with-libmbfl
        --with-onig

        # opcache
        # https://www.php.net/manual/en/book.opcache.php
        # --enable-opcache

        # pcntl
        # https://www.php.net/manual/en/book.pcntl.php
        --enable-pcntl

        # phar
        # https://www.php.net/manual/en/book.phar.php
        --enable-phar

        # readline
        # https://www.php.net/manual/en/book.readline.php
        --with-libedit
        --with-readline
        
        # recode
        # https://www.php.net/manual/en/book.recode.php
        --with-recode

        # shmop
        # https://www.php.net/manual/en/book.shmop.php
        --enable-shmop

        # snmp
        # https://www.php.net/manual/en/book.snmp.php
        --with-snmp

        # soap
        # https://www.php.net/manual/en/book.soap.php
        --enable-soap

        # sockets
        # https://www.php.net/manual/en/book.sockets.php
        --enable-sockets

        # sodium
        # https://www.php.net/manual/en/book.sodium.php
        # --with-sodium

        # password
        # https://www.php.net/manual/en/book.password.php
        # --with-password-argon2

        # sem
        # https://www.php.net/manual/en/book.sem.php
        --enable-sysvmsg
        --enable-sysvsem
        --enable-sysvshm

        # tidy
        # https://www.php.net/manual/en/book.tidy.php
        --with-tidy

        # tokenizer
        # https://www.php.net/manual/en/book.tokenizer.php
        --enable-tokenizer

        # wddx
        # https://www.php.net/manual/en/book.wddx.php
        --enable-wddx

        # xmlrpc
        # https://www.php.net/manual/en/book.xmlrpc.php
        --with-xmlrpc

        # xsl
        # https://www.php.net/manual/en/book.xsl.php
        --with-xsl

        # zip
        # https://www.php.net/manual/en/book.zip.php
        --enable-zip
        # --with-libzip

        # pear
        --with-pear

        # mysql
        # https://www.php.net/manual/en/set.mysqlinfo.php
        --enable-mysqlnd
        --with-mysql
        --with-mysqli=mysqlnd
        --with-pdo-mysql=mysqlnd
        # --with-mysql-sock=${HostSitesPath}/${MariaDB}/var/default/mariadb.socket
      )
      local Imagick=imagick-3.4.3
      local Redis=redis-4.3.0
      local Memcached=memcached-2.2.0
      local Swoole=swoole-1.9.17
      ;;
    *)
      help
      ;;
  esac

  for item in ${PhpConfigure[@]}; do
    echo ${item} >> ../option
  done

  ./configure ${PhpConfigure[@]} 2> ../configure.err

  make -j 8 2> ../make.err
  make -j 8 install 2> ../install.err

  # imagick
  # http://pecl.php.net/package/imagick
  # redis
  # https://pecl.php.net/package/redis
  # memcached
  # https://pecl.php.net/package/memcached
  # swoole
  # https://pecl.php.net/package/swoole
  for EXT in $Imagick $Redis $Memcached $Swoole; do
    # [ ! -f ${PhpSrcPath}/${EXT}.tgz ] && wget https://pecl.php.net/get/${EXT}.tgz -O ${PhpSrcPath}/${EXT}.tgz
    [ ! -f ${PhpSrcPath}/${EXT}.tgz ] && wget http://192.168.113.1:8080/${EXT}.tgz -O ${PhpSrcPath}/${EXT}.tgz
    tar -zxvf ${PhpSrcPath}/${EXT}.tgz -C ${PhpTarPath}
    cd ${PhpTarPath}/${EXT}
    ${PhpInstallPath}/bin/phpize
    ./configure --with-php-config=${PhpInstallPath}/bin/php-config
    make -j 8
    make -j 8 install
  done

  # composer
  echo
  echo
  echo "composer"
  cd ${PhpInstallPath}/bin
  curl -sS https://getcomposer.org/installer | ${PhpInstallPath}/bin/php

  # done
  echo
  echo
  echo ================================================================================
  echo "${Php} installation SUCCESS"
  echo "Configure:"
  for item in ${PhpConfigure[@]}; do
    echo "    ${item}"
  done
  echo ================================================================================
  echo
  echo

  exit 0
}

# php group 
# -v, --version
# -p, --prefix
# -n, --name
# php group -v=7.2.16 -p=/var/sites/www_test_com -n=frentend
# php group -v=7.2.16 -p=/var/sites/www_test_com -n=backend
group() {
  for i in "$@"; do
  case $i in
      -v=*|--version=*)
      local Version="${i#*=}"
      shift
      ;;
      -p=*|--prefix=*)
      local Prefix="${i#*=}"
      shift
      ;;
      -n=*|--name=*)
      local Name="${i#*=}"
      shift
      ;;
      *)

      ;;
  esac
  done

  if [ -z "$Version" ] || [ -z "$Prefix" ] || [ -z "$Name" ]; then
    help
    exit 1
  fi

  local Php=php-$Version
  local PhpInstallPath=${ToySoftPath}/${Php}

  if [ ! -d "${PhpInstallPath}" ]; then
    echo "${Php} is not installed yet"
    exit 1
  fi

  local NodePath=${Prefix}/${Php}

  local NodeData=${NodePath}/data
  local NodeEtc=${NodePath}/etc
  local NodeVar=${NodePath}/var
  local NodeLog=${NodePath}/log
  local NodeSystemd=${NodePath}/systemd

  local GroupData=${NodeData}/${Name}
  local GroupEtc=${NodeEtc}/${Name}
  local GroupVar=${NodeVar}/${Name}
  local GroupLog=${NodeLog}/${Name}

  if [ -d "${GroupEtc}" ]; then
    echo "${GroupEtc} already exists"
    exit 1
  fi

  mkdir -p ${NodeData}
  mkdir -p ${NodeEtc}
  mkdir -p ${NodeVar}
  mkdir -p ${NodeLog}
  mkdir -p ${NodeSystemd}

  mkdir -p ${GroupData}
  mkdir -p ${GroupEtc}
  mkdir -p ${GroupVar}
  mkdir -p ${GroupLog}
  mkdir -p ${GroupEtc}/php-fpm.d
  mkdir -p ${GroupEtc}/php-ini.d

  local UUID=$(echo -n ${GroupEtc} | openssl md5 | awk '{print $2}')

  # php-fpm@.server
  echo
  echo
  echo "php-fpm@.server"
  /bin/cp -v ${ToyCnfPath}/${Php}/php-fpm@.service ${NodeSystemd}/${Php}@${UUID}.service

  sed -i "/@Php@/{
    s//${Php}/g
    w /dev/stdout
  }" ${NodeSystemd}/${Php}@${UUID}.service
  sed -i "/@ENV@/{
    s::${GroupEtc}/env:g
    w /dev/stdout
  }" ${NodeSystemd}/${Php}@${UUID}.service
  sed -i "/@PHP_FPM@/{
    s::${PhpInstallPath}/sbin/php-fpm:g
    w /dev/stdout
  }" ${NodeSystemd}/${Php}@${UUID}.service

  systemctl enable ${NodeSystemd}/${Php}@${UUID}.service

  # php.ini
  # 不直接修改默认的配置文件，用 PHP_INI_SCAN_DIR 环境变量复写
  # 注：php-7.2.16 中 PHP_INI_SCAN_DIR 支持追加
  # 追加方式见 https://www.php.net/manual/en/configuration.file.php

  # https://github.com/php/php-src/blob/PHP-7.2.16/php.ini-development
  # https://github.com/php/php-src/blob/PHP-7.2.16/php.ini-production

  # https://github.com/php/php-src/blob/PHP-5.6.40/php.ini-development
  # https://github.com/php/php-src/blob/PHP-5.6.40/php.ini-production

  # https://github.com/php/php-src/blob/PHP-5.4.45/php.ini-development
  # https://github.com/php/php-src/blob/PHP-5.4.45/php.ini-production
  echo
  echo
  echo "php.ini"
  for cnf in php.ini-development php.ini-production; do
      /bin/cp -v ${ToyCnfPath}/${Php}/${cnf} ${GroupEtc}/${cnf}
  done

  ln -sv ${GroupEtc}/php.ini-development ${GroupEtc}/php-ini.d/php.ini

  # 复写 php.ini 默认配置
  echo
  echo
  echo "ext.ini"
  /bin/cp -v ${ToyCnfPath}/${Php}/ext.ini ${GroupEtc}/php-ini.d/ext.ini

  # php-fpm.conf
  # 注：php-7.2.16 中 pool 不包含在 php-fpm.conf 中
  # https://github.com/php/php-src/blob/PHP-7.2.16/sapi/fpm/php-fpm.conf.in

  # 注：php-5.6.40 中 pool 包含在 php-fpm.conf 中
  # 删除主配置文件时的 pool 并添加 include 配置项
  # https://github.com/php/php-src/blob/PHP-5.6.40/sapi/fpm/php-fpm.conf.in

  # 注：php-5.4.45 中 pool 包含在 php-fpm.conf 中
  # 删除主配置文件时的 pool 并添加 include 配置项
  # https://github.com/php/php-src/blob/PHP-5.4.45/sapi/fpm/php-fpm.conf.in
  echo
  echo
  echo "php-fpm.conf"
  /bin/cp -v ${ToyCnfPath}/${Php}/php-fpm.conf ${GroupEtc}/php-fpm.conf

  if [ "$Version" == "7.2.16" ]; then
    echo
  else
    sed -i "126,\$d" ${GroupEtc}/php-fpm.conf
    sed -i "\$a include=@php_fpm_sysconfdir@/php-fpm.d/*.conf" ${GroupEtc}/php-fpm.conf
  fi

  sed -i "/@prefix@/{
    s::${PhpInstallPath}:g
    w /dev/stdout
  }" ${GroupEtc}/php-fpm.conf
  sed -i "/@EXPANDED_LOCALSTATEDIR@/{
    s::${GroupVar}:g
    w /dev/stdout
  }" ${GroupEtc}/php-fpm.conf
  sed -i "/;pid = run\/php-fpm.pid/{
    s::pid = ${GroupVar}/php-fpm.pid:g
    w /dev/stdout
  }" ${GroupEtc}/php-fpm.conf
  sed -i "/;error_log = log\/php-fpm.log/{
    s::error_log = ${GroupLog}/php-fpm.log:g
    w /dev/stdout
  }" ${GroupEtc}/php-fpm.conf
  sed -i "/@php_fpm_sysconfdir@/{
    s::${GroupEtc}:g
    w /dev/stdout
  }" ${GroupEtc}/php-fpm.conf

  # env
  echo
  echo
  echo "env"
  /bin/cp -v ${ToyCnfPath}/${Php}/env ${GroupEtc}/env

  sed -i "/@PID@/{
    s::${GroupVar}/php-fpm.pid:g
    w /dev/stdout
  }" ${GroupEtc}/env
  sed -i "/@CONF@/{
    s::${GroupEtc}/php-fpm.conf:g
    w /dev/stdout
  }" ${GroupEtc}/env
  sed -i "/@PHP_INI_SCAN_DIR@/{
    s::${GroupEtc}/php-ini.d:g
    w /dev/stdout
  }" ${GroupEtc}/env

  echo
  return 0
}

# php server
# -v, --version
# -p, --prefix
# -n, --name
# -s, --server
# -u, --user
# -g, --group
# php server -v=7.2.16 -p=/var/sites/www_test_com -n=frentend -s=www -u=php-7.2.16 -g=www
# php server -v=7.2.16 -p=/var/sites/www_test_com -n=backend -s=www -u=php-7.2.16 -g=www
server() {
  for i in "$@"; do
  case $i in
      -v=*|--version=*)
      local Version="${i#*=}"
      shift
      ;;
      -p=*|--prefix=*)
      local Prefix="${i#*=}"
      shift
      ;;
      -n=*|--name=*)
      local Name="${i#*=}"
      shift
      ;;
      -s=*|--server=*)
      local Server="${i#*=}"
      shift
      ;;
      -u=*|--user=*)
      local User="${i#*=}"
      shift
      ;;
      -g=*|--group=*)
      local Group="${i#*=}"
      shift
      ;;
      *)

      ;;
  esac
  done

  if [ -z "$Version" ] || [ -z "$Prefix" ] || [ -z "$Name" ] || [ -z "$Server" ]; then
    help
    exit 1
  fi

  local Php=php-$Version
  local PhpInstallPath=${ToySoftPath}/${Php}

  local User=${User:-${Php}}
  local Group=${Group:-www}

  if [ ! -d "${PhpInstallPath}" ]; then
    echo "${Php} is not installed yet"
    exit 1
  fi

  local NodePath=${Prefix}/${Php}

  local NodeData=${NodePath}/data
  local NodeEtc=${NodePath}/etc
  local NodeVar=${NodePath}/var
  local NodeLog=${NodePath}/log
  local NodeSystemd=${NodePath}/systemd

  local GroupData=${NodeData}/${Name}
  local GroupEtc=${NodeEtc}/${Name}
  local GroupVar=${NodeVar}/${Name}
  local GroupLog=${NodeLog}/${Name}

  if [ ! -d "${GroupEtc}" ]; then
    echo "${GroupEtc} not exists"
    exit 1
  fi

  local ServerEtc=${GroupEtc}/php-fpm.d/${Server}.conf
  local ServerSocket=${GroupVar}/${Server}.socket
  local ServerAccessLog=${GroupLog}/${Server}.access.log
  local ServerErrorLog=${GroupLog}/${Server}.error.log
  local ServerSlowLog=${GroupLog}/${Server}.slow.log

  if [ -f "${ServerEtc}" ]; then
    echo "${ServerEtc} already exists"
    exit 1
  fi

  # www.conf
  # https://github.com/php/php-src/blob/PHP-7.2.16/sapi/fpm/www.conf.in
  # https://github.com/php/php-src/blob/PHP-5.6.40/sapi/fpm/www.conf.in

  # 注：php-5.4.45 中 pool 包含在 php-fpm.conf 中，没有单独 pool 文件，从主配置文件中提取
  echo
  echo
  echo "www.conf"
  /bin/cp -v ${ToyCnfPath}/${Php}/www.conf ${ServerEtc}

  sed -i "/\[www\]/{
    s::[${Server}]:g
    w /dev/stdout
  }" ${ServerEtc}

  sed -i "/@prefix@/{
    s::${PhpInstallPath}:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/@php_fpm_prefix@/{
    s::${PhpInstallPath}:g
    w /dev/stdout
  }" ${ServerEtc}

  sed -i "/listen = 127.0.0.1\:9000/{
    s::;listen = 127.0.0.1\:9000:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;listen = 127.0.0.1\:9000/a listen = @SOCK@" ${ServerEtc};
  sed -i "/listen = @SOCK@/{
    s::listen = ${ServerSocket}:g
    w /dev/stdout
  }" ${ServerEtc}
  
  sed -i "/;listen.owner = @php_fpm_user@/{
    s::listen.owner = @php_fpm_user@:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;listen.group = @php_fpm_group@/{
    s::listen.group = @php_fpm_group@:g
    w /dev/stdout
  }" ${ServerEtc}

  sed -i "/@php_fpm_user@/{
    s::${User}:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/@php_fpm_group@/{
    s::${Group}:g
    w /dev/stdout
  }" ${ServerEtc}

  sed -i "/;access.log = log\/\$pool.access.log/{
    s::access.log = ${ServerAccessLog}:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;slowlog = log\/\$pool.log.slow/{
    s::slowlog = ${ServerSlowLog}:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;request_slowlog_timeout = 0/{
    s::request_slowlog_timeout = 5s:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;php_admin_flag\[log_errors\] = on/{
    s::php_admin_flag\[log_errors\] = on:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/;php_admin_value\[error_log\] = \/var\/log\/fpm-php.www.log/{
    s::php_admin_value\[error_log\] = ${ServerErrorLog}:g
    w /dev/stdout
  }" ${ServerEtc}
  sed -i "/@EXPANDED_DATADIR@/{
    s::${PhpInstallPath}\/php\/php:g
    w /dev/stdout
  }" ${ServerEtc}

  # user&group
  echo
  echo
  getent group ${Group} &>/dev/null || (groupadd ${Group} && 
    echo "groupadd ${Group}")
  getent passwd ${User} &>/dev/null || (useradd -s /sbin/nologin -d /dev/null -M -g ${Group} ${User} &&
    echo "useradd -s /sbin/nologin -d /dev/null -M -g ${Group} ${User}")

  # pool 的 error.log 生成不了解决方法
  # https://stackoverflow.com/questions/8677493/php-fpm-doesnt-write-to-error-log/23223585#23223585
  touch ${ServerErrorLog}
  chown ${User}:${Group} ${ServerErrorLog}

  return 0
}

case "$1" in
  install)
    shift
    install "$@"
    ;;
  group)
    shift
    group "$@"
    ;;
  server)
    shift
    server "$@"
    ;;
  *)
    help
    ;;
esac