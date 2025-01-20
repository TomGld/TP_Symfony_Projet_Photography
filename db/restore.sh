#!/usr/bin/sh

mariadb symfony_photography_tomf -uroot -psuperAdmin < /root/init.sql
echo "Restauration terminée"
