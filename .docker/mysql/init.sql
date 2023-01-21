# create databases
CREATE DATABASE IF NOT EXISTS `revendo_dev`;
CREATE DATABASE IF NOT EXISTS `revendo_test`;

# create root user and grant rights
UPDATE mysql.user SET host = '%' where user = 'root;'