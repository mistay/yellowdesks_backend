#!/bin/bash

source deploy.conf

if [ -f tmp/${LOCAL_DB_NAME}.sql ]; then
	rm tmp/${LOCAL_DB_NAME}.sql
	echo "removed old tmp/${LOCAL_DB_NAME}.sql"
fi
if [ -f tmp/${LOCAL_DB_NAME}.sql.bz2 ]; then
	rm tmp/${LOCAL_DB_NAME}.sql.bz2
	echo "removed old tmp/${LOCAL_DB_NAME}.sql.bz2"
fi

echo "dumping database to file"
$mysqldump -u ${LOCAL_DB_USER} -p${LOCAL_DB_PASS} ${LOCAL_DB_NAME} $FULL --add-drop-database > tmp/${LOCAL_DB_NAME}.sql

echo "zipping database dump"
bzip2 -9 tmp/${LOCAL_DB_NAME}.sql

echo "copy db dump to ${REMOTE_HOST}"
scp tmp/${LOCAL_DB_NAME}.sql.bz2 root@${REMOTE_HOST}:.

echo "dropping remote database"
ssh root@${REMOTE_HOST} "echo 'SET foreign_key_checks = 0;' > drop.sql"
ssh root@${REMOTE_HOST} "mysqldump -u root -p${REMOTE_DB_PASS} $FULL --no-data ${REMOTE_DB_NAME} | grep ^DROP >> drop.sql"
ssh root@${REMOTE_HOST} "mysql -u root -p${REMOTE_DB_PASS} ${REMOTE_DB_NAME} < drop.sql"

echo "restoring database dump on ${REMOTE_HOST}"
ssh root@${REMOTE_HOST} "rm ${LOCAL_DB_NAME}.sql; bunzip2 ${LOCAL_DB_NAME}.sql.bz2 && mysql -p${REMOTE_DB_PASS} ${REMOTE_DB_NAME}< ${LOCAL_DB_NAME}.sql"

