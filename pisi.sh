sudo pisi ur
sudo pisi it curl net-tools apache php php-intl php-curl -y 
a2enmod ssl 
sudo pisi it  mplayer -y
sudo pisi it  ntpdate alsa-utils alsa-tools -y
cp admin /var/www/html/. -r 
cp img-panel /var/www/html/. -r 
cp files /var/www/html/. -r
cp sounds /var/www/html/. -r
cp triggers /var/www/html/. -r
cp index.php /var/www/html/.
cp etc /. -r
mkdir /var/www/html/triggers/time/0 
mkdir /var/www/html/triggers/time/1 
mkdir /var/www/html/triggers/time/2 
mkdir /var/www/html/triggers/time/3 
mkdir /var/www/html/triggers/time/4 
mkdir /var/www/html/triggers/time/5 
mkdir /var/www/html/triggers/time/6 
cd /var/www/html 
chown www-data:www-data * -R
mkdir /var/config/
echo "21232f297a57a5a743894a0e4a801fc3" >/var/config/users
chown www-data:www-data /var/config -R
echo >/var/www/html/triggers/nowplayingv
echo >/var/www/html/triggers/nowlock
sudo chmod +x /etc/rc.localmy
systemctl enable rc-localmy
systemctl restart apache2
grep -qxF '*/1     * * * * www-data  cd /var/www/html/admin/startscript; php -q ./soundplay.php' /etc/crontab || echo '*/1     * * * * www-data  cd /var/www/html/admin/startscript; php -q ./soundplay.php' >>/etc/crontab
cd /usr/bin; ln -s mplayer myplayersN.bin
echo "Adjusting Date Time"
ntpdate  tr.pool.ntp.org
echo "Installation completed. Please reboot system."
