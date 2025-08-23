#!/bin/bash

echo "Updating apt package index..."
sudo apt-get update

echo "Installing php-mysql extension for PHP..."
sudo apt-get install -y php-mysql

echo "Restarting the Symfony server to reload PHP extensions..."
symfony server:stop
symfony server:start --allow-all-ip

echo "Verifying pdo_mysql extension is loaded:"
php -m | grep pdo_mysql

echo "If pdo_mysql appears above, your installation is successful."
