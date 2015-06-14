#!/bin/bash
bucket="opendrop"

clean=""
list=$(aws s3 ls s3://$bucket --recursive)
while read -r line; do
    fromdate=$(echo "$line" | awk '{print $1,$2}')
    todate=$(date -d "$fromdate 1 day" +"%s")
    now=$(date +"%s")
    if [ $now -ge $todate ]; then
        path=$(echo "$line" | awk '{print substr($0, index($0,$4))}')
        drop="${path:0:4}"
        if [[ $clean != *"$drop"* ]]; then
            if [[ $clean != "" ]]; then
                clean="$clean"$'\n'
            fi
            clean="$clean$drop"
        fi
    fi
done <<< "$list"
if [[ $clean != "" ]]; then
    while read -r line; do
        aws s3 rm s3://$bucket/$line --recursive
    done <<< "$clean"
fi
