#!/usr/bin/sh
mariadb-dump symfony_photography_tomf -uroot -psuperAdmin > /root/init.sql
echo "Sauvegarde terminée"