#!/bin/bash

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;36m'
NC='\033[0m' # No color

DB_PASSWORD="wordpress"
DB_USERNAME="wordpress"
DB_DATABASE="wordpress"

# Redirect STDOUT and STDERR to provision.log 
exec > >(tee provision.log)
exec 2> >(tee provision.log >&2)

echo -e "${BLUE}Provisioning virtual machine...${NC}"

sudo apt-get update -y

echo -e "Installing Git${NC}"
apt-get install git -y

echo -e "${GREEN}Installing Nginx${NC}"
apt-get install nginx -y

echo -e "${GREEN}Updating PHP repository${NC}"
apt-get install python-software-properties build-essential -y
add-apt-repository ppa:ondrej/php5 -y
apt-get update

echo -e "${GREEN}Installing PHP${NC}"
apt-get install php5-common php5-dev php5-cli php5-fpm -y
 
echo -e "${GREEN}Installing PHP extensions${NC}"
apt-get install curl php5-curl php5-gd php5-mcrypt php5-pgsql -y

echo -e "${GREEN}Installing PostgreSQL${NC}"
apt-get install postgresql -y

echo "CREATE ROLE $DB_USERNAME WITH LOGIN ENCRYPTED PASSWORD '$DB_PASSWORD';" | sudo -u postgres psql
su postgres -c "createdb $DB_DATABASE --owner $DB_USERNAME"
service postgresql reload

echo -e "${GREEN}Configuring Nginx${NC}"
cp /app/provision/config/nginx_vhost /etc/nginx/sites-available/nginx_vhost
ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/
rm -rf /etc/nginx/sites-available/default
service nginx restart

echo -e "${GREEN}Installing Composer${NC}"
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
chmod 755 /usr/local/bin/composer

echo -e "${GREEN}Building${NC}"
sudo -u vagrant composer --working-dir=/app install

echo -e "${BLUE}Done!${NC}"
