#git pull origin
sudo rm -rf /var/www/coachtech-advance
sudo cp -r ~/prog/coachtech-advance /var/www/
sudo chown -R apache:apache /var/www/coachtech-advance
sudo chmod -R 755 /var/www/coachtech-advance
sudo systemctl restart httpd
