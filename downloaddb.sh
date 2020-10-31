#!/bin/bash

source dbdeploy.conf

if [ -f tmp/yellowdesks.sql ]; then
	rm tmp/yellowdesks.sql
fi
if [ -f tmp/yellowdesks.sql.bz2 ]; then
        rm tmp/yellowdesks.sql.bz2
fi

ssh root@${REMOTE_HOST} "rm /tmp/yellowdesks.sql.bz2 2>/dev/null"
ssh root@${REMOTE_HOST} "rm /tmp/yellowdesks.sql 2>/dev/null"
ssh root@${REMOTE_HOST} "mysqldump -u ${REMOTE_DB_USER} -p${REMOTE_DB_PASS} ${REMOTE_DB_NAME} $FULL --add-drop-database > /tmp/${REMOTE_DB_NAME}.sql"
ssh root@${REMOTE_HOST} "bzip2 -9 /tmp/${REMOTE_DB_NAME}.sql"
scp root@${REMOTE_HOST}:/tmp/${REMOTE_DB_NAME}.sql.bz2 tmp/

echo 'SET foreign_key_checks = 0;' > tmp/drop.sql
$mysqldump -u ${LOCAL_DB_USER} -proot $FULL --no-data ${LOCAL_DB_NAME} | grep ^DROP >> tmp/drop.sql
$mysql -u ${LOCAL_DB_USER} ${LOCAL_DB_NAME} < tmp/drop.sql

bunzip2 tmp/${LOCAL_DB_NAME}.sql.bz2 && $mysql -u root ${LOCAL_DB_NAME} < tmp/${REMOTE_DB_NAME}.sql

