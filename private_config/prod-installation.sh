#!/bin/bash

BRANCH_NAME=$1
HOSTNAME=$2

MYSQL_ROOT_PASSWORD=R00t3DuC4C1Ty
MYSQL_EDUCACITY_INS_PASSWORD=1Ns3DuC4C1Ty
MYSQL_EDUCACITY_SEL_PASSWORD=S3l3DuC4C1Ty

DPATH=/var/www/educacity
SOURCES=/root/sources

mkdir -p $SOURCES

echo "*************************************"
echo "***       Installing Git          ***"
echo "*************************************"
apt-get --yes install git-core

echo "***********************************"
echo "* Installing gcc *"
echo "***********************************"
apt-get --yes install gcc

echo "*************************************"
echo "***    Install local configs      ***"
echo "*************************************"
cp -R ./etc/* /etc/
hostname $HOSTNAME
echo "127.0.0.1 $HOSTNAME dbins.local.iventiajobs.com dbsel.local.iventiajobs.com" >> /etc/hosts

echo "*************************************"
echo "***    Users and permissions      ***"
echo "*************************************"
adduser --disabled-password --gecos "" educacity
adduser educacity www-data
chpasswd <<__END__
educacity:pqe!_manu
__END__

echo "*************************************"
echo "***       Update sources          ***"
echo "*************************************"
apt-get --yes update && apt-get --yes upgrade

echo "*************************************"
echo "***            SUDO               ***"
echo "*************************************"
aptitude install sudo
echo "iventia    ALL=(ALL) ALL" >> /etc/sudoers

echo "*************************************"
echo "***       Installing PHP          ***"
echo "*************************************"
apt-get --yes install php5 php5-common php5-intl php5-gd php-apc php5-imap php5-curl php5-mysql php5-sqlite php5-imagick php5-ffmpeg imagemagick

echo "*************************************"
echo "***     Installing apache         ***"
echo "*************************************"
apt-get --yes install apache2 apache2-utils

echo "*************************************"
echo "***      Installing MySQL         ***"
echo "*************************************"
DEBIAN_FRONTEND=noninteractive apt-get --yes -q install mysql-server

# change root password by default
mysqladmin -u root password $MYSQL_ROOT_PASSWORD
# add iventia_ins user
mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "grant select,insert,update,delete,lock tables on educacity.* to 'educacity_ins'@'%' IDENTIFIED BY '$MYSQL_EDUCACITY_INS_PASSWORD';"
# add iventia_sel user
mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "grant select on educacity.* to 'educacity_sel'@'%' IDENTIFIED BY '$MYSQL_EDUCACITY_SEL_PASSWORD';"

mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "flush privileges;"
# mysql iventiajobs database
mysql -uroot -p$MYSQL_ROOT_PASSWORD -e 'create database educacity character set = "utf8" collate "utf8_spanish_ci";'

sed -i 's/bind-address.*/bind-address            = 0.0.0.0/g' /etc/mysql/my.cnf
service mysql restart

echo "*************************************"
echo "***      Deploy Iventia app       ***"
echo "*************************************"
cp -a ~/.ssh/id_rsa* ~/.ssh/known_hosts ~iventia/.ssh/
cd /home/iventia
cp -a /root/git/bin .
chown -R iventia:iventia .ssh bin
su iventia -c "mkdir git"
cd git
su iventia -c "git clone git@github.com:criscosg/iventia2.git iventia"
mkdir -p $DPATH
chown -R iventia:www-data $DPATH
chmod 755 /home/iventia/bin/sincroniza.sh
chmod 755 /home/iventia/bin/release.sh
su - iventia -c "sincroniza.sh"

###
#ln -s /home/iventia/git/iventia2 $DPATH
#chown -R iventia:www-data $DPATH
#chmod 755 /home/iventia/bin/sincroniza.sh 
#su - iventia -c "sincroniza.sh" #rsync
###

echo "*************************************"
echo "***   Install Mail MTA            ***"
echo "*************************************"
DEBIAN_FRONTEND=noninteractive apt-get -y install postfix

echo "*************************************"
echo "***   Enable Iventia jobs app     ***"
echo "*************************************"
a2enmod rewrite
a2ensite iventia.com
service apache2 restart

echo "*************************************"
echo "***   Doing some little configs.  ***"
echo "*************************************"
#cd /var/lib/mysql; mklost+found
#touch /etc/popularity-contest.conf
#mkdir /var/log/upstart
#echo soporte@iventia.com > ~/.forward

echo "*************************************"
echo "***       Install lockrun         ***"
echo "*************************************"
wget http://www.unixwiz.net/tools/lockrun.c
gcc lockrun.c -o lockrun
cp lockrun /usr/local/bin/

cat > /etc/cron.d/iventia-local <<__END__
MAILTO=soporte@iventiajobs.com
LOGS=/var/log/iventia-local.log

# Ensuring the last version of crontab
* 15 * * *    root    rsync -t /var/www/iventia/scripts/crontab-backend /etc/cron.d/iventia-crontab-backend
__END__

echo -e '\E[01;32m*************************************'
echo -e '\E[01;32m***            DONE!              ***'
echo -e '\E[01;32m*************************************\033[00m'
uptime


<VirtualHost *:80>
    ServerName      educacity-sevilla.com
    ServerAlias     www.educacity-sevilla.com

    DocumentRoot    "/var/www/educacity/web"
    DirectoryIndex  app.php

    <Directory "/var/www/educacity/web">
        AllowOverride None
        Allow from All

        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
    </Directory>

    CustomLog  /var/log/httpd/educacity-access.log combined

    KeepAlive            On
    MaxKeepAliveRequests 200
    KeepAliveTimeout     5

    AddOutputFilterByType DEFLATE text/css text/plain text/html application/xhtml+xml text/xml application/xml

    <IfModule mod_headers.c>
        Header append Vary User-Agent env=!dont-vary

        ExpiresActive On
        ExpiresDefault "now plus 1 week"
        ExpiresByType image/x-icon "now plus 1 month"
        ExpiresByType image/gif    "now plus 1 month"
        ExpiresByType image/png    "now plus 1 month"
        ExpiresByType image/jpeg   "now plus 1 month"
    </IfModule>
</VirtualHost>

<VirtualHost *:80>
        ServerName      educacity-sevilla.com
        ServerAlias     www.educacity-sevilla.com

        DocumentRoot    "/var/www/educacity/web"
        DirectoryIndex  app.php
        AddDefaultCharset utf-8

        <Directory /var/www/educacity/web>
                Options FollowSymLinks MultiViews

                AllowOverride None
                Allow from All

                <IfModule mod_rewrite.c>
                    Options -MultiViews
                    RewriteEngine On
                    RewriteCond %{REQUEST_FILENAME} !-f
                    RewriteRule ^(.*)$ app.php [QSA,L]
                </IfModule>
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/educacity-error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel debug

        CustomLog ${APACHE_LOG_DIR}/educacity-access.log combined
</VirtualHost>
<VirtualHost *:80>
        ServerName educacity-sevilla.com
    Redirect 301 / http://www.educacity-sevilla.com/
</VirtualHost>
