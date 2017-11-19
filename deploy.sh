#!/bin/bash

source deploy.conf

for server in $REMOTE_MACHINES
do
    echo "deploying on $server"
    ssh root@${server} "cd $REMOTE_BASE_DIR; svn up; bin/cake orm_cache clear"
done
