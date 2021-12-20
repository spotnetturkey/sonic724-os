sudo pisi ur
sudo pisi it curl net-tools apache php php-intl php-curl -y 
a2enmod ssl 
sudo pisi it  mplayer -y
sudo pisi it  ntpdate alsa-utils alsa-tools -y
cp admin /var/www/localhost/htdocs/. -r 
cp img-panel /var/www/localhost/htdocs/. -r 
cp files /var/www/localhost/htdocs/. -r
cp sounds /var/www/localhost/htdocs/. -r
cp triggers /var/www/localhost/htdocs/. -r
cp index.php /var/www/localhost/htdocs/.
cp etc /. -r
mkdir /var/www/localhost/htdocs/triggers/time/0 
mkdir /var/www/localhost/htdocs/triggers/time/1 
mkdir /var/www/localhost/htdocs/triggers/time/2 
mkdir /var/www/localhost/htdocs/triggers/time/3 
mkdir /var/www/localhost/htdocs/triggers/time/4 
mkdir /var/www/localhost/htdocs/triggers/time/5 
mkdir /var/www/localhost/htdocs/triggers/time/6 
cd /var/www/localhost/htdocs 
chown www-data:www-data * -R
mkdir /var/config/
echo "21232f297a57a5a743894a0e4a801fc3" >/var/config/users
chown www-data:www-data /var/config -R
echo >/var/www/localhost/htdocs/triggers/nowplayingv
echo >/var/www/localhost/htdocs/triggers/nowlock
sudo chmod +x /etc/rc.localmy
systemctl enable rc-localmy
systemctl restart apache2
grep -qxF '*/1     * * * * www-data  cd /var/www/localhost/htdocs/admin/startscript; php -q ./soundplay.php' /etc/crontab || echo '*/1     * * * * www-data  cd /var/www/localhost/htdocs/admin/startscript; php -q ./soundplay.php' >>/etc/crontab
cd /usr/bin; ln -s mplayer myplayersN.bin
echo "Adjusting Date Time"
ntpdate  tr.pool.ntp.org
echo "Installation completed. Please reboot system."
