#!/usr/bin/env bash

# Name: const;
# Desc: an auto web server installation tool;
# Date: 2013-02-07 by garryshield;

source ./const;
source ./funs;

echo "${Php} installation";
echo 'pleace input <Enter> twice to continue or <CTRL+C> to exit:';
read ;
read ;


# uninstall
if [ -d ${PhpInstallPath} ]; then
  systemctl stop ${Php}.service;
  systemctl disable ${Php}.service;

  # firewall-cmd --permanent --zone=public --remove-port=${PhpPort}/tcp;
  # firewall-cmd --reload;

  mv ${HostSitesPath}/${Php} ${HostSitesPath}/${Php}-`date '+%Y-%m-%d(%H:%M:%S)'`;
  mv ${PhpInstallPath} ${PhpInstallPath}-`date '+%Y-%m-%d(%H:%M:%S)'`;
fi;

# rm -rf ${PhpSrcPath};
rm -rf ${PhpTarPath};

# download
mkdir -p ${PhpSrcPath};
if [ ! -f ${PhpPackPath} ]; then
    wget ${PhpUrlPath} -O ${PhpPackPath};
fi;

# const
PhpEtcPath=${HostSitesPath}/${Php}/etc;
mkdir -p ${PhpEtcPath}/php-fpm.d;

PhpLogPath=${HostSitesPath}/${Php}/log;
mkdir -p ${PhpLogPath};

PhpVarPath=${HostSitesPath}/${Php}/var;
mkdir -p ${PhpVarPath};

PhpDataPath=${HostSitesPath}/${Php}/data;
mkdir -p ${PhpDataPath};

PhpSysPath=${HostSitesPath}/${Php}/systemd;
mkdir -p ${PhpSysPath};

# user & group
remove_user ${PhpUser};
remove_group ${PhpGroup};
groupadd ${PhpGroup};
useradd -s /sbin/nologin -d /dev/null -M -g ${PhpUser} ${PhpGroup};

# port
# firewall-cmd --permanent --zone=public --add-port=${PhpPort}/tcp;
# firewall-cmd --reload;

# installation
mkdir -p ${PhpTarPath};

tar -zxvf ${PhpPackPath} -C ${PhpTarPath};
cd ${PhpTarPath}/${Php};

# configure
./configure --help > ../configure;

PhpConfigure="\
  --prefix=${PhpInstallPath} \

  --enable-fpm \
  --with-fpm-user=${PhpUser} \
  --with-fpm-group=${PhpGroup} \
  --with-fpm-systemd \
  --with-fpm-acl \
  --with-litespeed \
  --enable-phpdbg \
  --enable-phpdbg-webhelper \
  --enable-phpdbg-debug \

  --enable-debug \
  --with-config-file-path=${PhpEtcPath} \
  --with-config-file-scan-dir=${PhpEtcPath}/php.ini.d \
  --enable-sigchild \
  --enable-libgcc \
  --enable-dtrace \
  --enable-fd-setsize=2048 \

  --with-libxml-dir \
  --with-openssl \
  --with-system-ciphers \
  --with-pcre-regex \
  --with-pcre-jit \
  --with-zlib \
  --enable-bcmath \
  --with-bz2 \
  --enable-calendar \
  --with-curl \
  --with-enchant \
  --enable-exif \
  --with-pcre-dir \
  --enable-ftp \
  --with-openssl-dir \
  --with-gd \
  --with-webp-dir \
  --with-jpeg-dir \
  --with-png-dir \
  --with-zlib-dir \
  --with-xpm-dir \
  --with-freetype-dir \
  --enable-gd-jis-conv \
  --with-gettext \
  --with-gmp \
  --with-mhash \
  --enable-intl \
  --enable-mbstring \
  --with-libmbfl \
  --with-onig \
  --enable-pcntl \
  --with-libedit \
  --with-readline \
  --with-recode \
  --enable-shmop \
  --with-snmp \
  --enable-soap \
  --enable-wddx \
  --with-xmlrpc \
  --with-iconv-dir \
  --with-xsl \
  --enable-zip \
  --with-zlib-dir \
  --with-pcre-dir \

  --with-pear \

  --enable-maintainer-zts \

  --with-mysqli=mysqlnd \
  --with-pdo-mysql=mysqlnd \
  --with-mysql-sock=${HostSitesPath}/${MariaDB}/var/mysql.sock \
  --enable-mysqlnd \
";

echo ${PhpConfigure} > ../option;

./configure ${PhpConfigure} 2> ../configure.err;

make -j 8 2> ../make.err;
make -j 8 install 2> ../install.err;

# imagick
[ ! -f ${PhpSrcPath}/imagick-3.4.3.tgz ] && wget http://pecl.php.net/get/imagick-3.4.3.tgz -O ${PhpSrcPath}/imagick-3.4.3.tgz;
tar -zxvf ${PhpSrcPath}/imagick-3.4.3.tgz -C ${PhpTarPath};
cd ${PhpTarPath}/imagick-3.4.3;
${PhpInstallPath}/bin/phpize;
./configure --with-php-config=${PhpInstallPath}/bin/php-config;
make -j 8;
make -j 8 install;

# memcached
[ ! -f ${PhpSrcPath}/memcached-3.0.4.tgz ] && wget http://pecl.php.net/get/memcached-3.0.4.tgz -O ${PhpSrcPath}/memcached-3.0.4.tgz;
tar -zxvf ${PhpSrcPath}/memcached-3.0.4.tgz -C ${PhpTarPath};
cd ${PhpTarPath}/memcached-3.0.4;
${PhpInstallPath}/bin/phpize;
./configure --with-php-config=${PhpInstallPath}/bin/php-config;
make -j 8;
make -j 8 install;

# redis
[ ! -f ${PhpSrcPath}/redis-4.1.1.tgz ] && wget http://pecl.php.net/get/redis-4.1.1.tgz -O ${PhpSrcPath}/redis-4.1.1.tgz;
tar -zxvf ${PhpSrcPath}/redis-4.1.1.tgz -C ${PhpTarPath};
cd ${PhpTarPath}/redis-4.1.1;
${PhpInstallPath}/bin/phpize;
./configure --with-php-config=${PhpInstallPath}/bin/php-config;
make -j 8;
make -j 8 install;

# swoole
[ ! -f ${PhpSrcPath}/swoole-src-4.0.3.tar.gz ] && wget http://github.com/swoole/swoole-src/archive/v4.0.3.tar.gz -O ${PhpSrcPath}/swoole-src-4.0.3.tar.gz;
tar -zxvf ${PhpSrcPath}/swoole-src-4.0.3.tar.gz -C ${PhpTarPath};
cd ${PhpTarPath}/swoole-src-4.0.3;
${PhpInstallPath}/bin/phpize;
./configure --with-php-config=${PhpInstallPath}/bin/php-config;
make -j 8;
make -j 8 install;

# php.ini
echo ;
echo ;
echo "Install php.ini file";
for cnf in php.ini-development php.ini-production; do
    /bin/cp ${HostSetUpCnfPath}/${Php}/${cnf} ${PhpEtcPath}/${cnf} && \
        echo "/bin/cp ${HostSetUpCnfPath}/${Php}/${cnf} ${PhpEtcPath}/${cnf}";
    
    sed -i "s:short_open_tag = Off:short_open_tag = On:" ${PhpEtcPath}/${cnf};
    sed -i "s:zlib.output_compression = Off:zlib.output_compression = On:" ${PhpEtcPath}/${cnf};

    sed -i "s:;date.timezone =:date.timezone = Asia/Shanghai:" ${PhpEtcPath}/${cnf};
done;

ln -s ${PhpEtcPath}/php.ini-development ${PhpEtcPath}/php.ini && \
    echo "ln -s ${PhpEtcPath}/php.ini-development ${PhpEtcPath}/php.ini";

# php-fpm.conf
echo ;
echo ;
echo "php-fpm.conf";
/bin/cp ${HostSetUpCnfPath}/${Php}/php-fpm.conf ${PhpEtcPath}/php-fpm.conf && \
    echo "${HostSetUpCnfPath}/${Php}/php-fpm.conf ${PhpEtcPath}/php-fpm.conf";

sed -i "s:@INSTALL_PATH@:${PhpInstallPath}:" ${PhpEtcPath}/php-fpm.conf;
sed -i "s:@PID_FILE@:${PhpVarPath}/php-fpm.pid:" ${PhpEtcPath}/php-fpm.conf;
sed -i "s:@ERROR_LOG_FILE@:${PhpLogPath}/php-fpm.log:" ${PhpEtcPath}/php-fpm.conf;
sed -i "s:@ETC_PATH@:${PhpEtcPath}:" ${PhpEtcPath}/php-fpm.conf;
sed -i "s:@HTDOCS@:${HostSitesWebPath}:" ${PhpEtcPath}/php-fpm.conf;
sed -i "s:@PHP@:${Php}:" ${PhpEtcPath}/php-fpm.conf;

/bin/cp ${HostSetUpCnfPath}/${Php}/php-fpm.d/www.conf ${PhpEtcPath}/php-fpm.d/www.conf && \
    echo "${HostSetUpCnfPath}/${Php}/php-fpm.d/www.conf ${PhpEtcPath}/php-fpm.d/www.conf";

sed -i "s:@INSTALL_PATH@:${PhpInstallPath}:" ${PhpEtcPath}/php-fpm.d/www.conf;
sed -i "s:@USER@:${PhpUser}:" ${PhpEtcPath}/php-fpm.d/www.conf;
sed -i "s:@GROUP@:${PhpGroup}:" ${PhpEtcPath}/php-fpm.d/www.conf;

# php-fpm.service
echo ;
echo ;
echo "php-fpm.service";
/bin/cp ${HostSetUpCnfPath}/${Php}/php-fpm.service ${PhpSysPath}/${Php}.service && \
    echo "${HostSetUpCnfPath}/${Php}/php-fpm.service ${PhpSysPath}/${Php}.service";

sed -i "s:@PID_FILE@:${PhpVarPath}/php-fpm.pid:" ${PhpSysPath}/${Php}.service;
sed -i "s:@PHP_FPM@:${PhpInstallPath}/sbin/php-fpm:" ${PhpSysPath}/${Php}.service;
sed -i "s:@FPM_CONFIG@:${PhpEtcPath}/php-fpm.conf:" ${PhpSysPath}/${Php}.service;

systemctl enable ${PhpSysPath}/${Php}.service;

# composer
cd ${PhpInstallPath}/bin;
curl -sS https://getcomposer.org/installer | ${PhpInstallPath}/bin/php;

# done
echo ;
echo ;
echo ================================================================================;
echo "${Php} installation SUCCESS";
echo "Configure Command:";
for itm in ${MariaDBConfigure}; do
	echo "	"${itm};
done;
echo "systemctl {start|stop|reload} ${Php}";
echo ================================================================================;
echo ;
echo ;

exit 0;