#!/bin/bash
SERVERS=('121.41.88.209' '115.29.8.55')
echo '-------------'${#SERVERS[@]}
for index in ${SERVERS[@]}
do
  echo '---------'$index
done

  for(( count=0;count<=${#SERVERS[@]}-1;count+=1 ))

   do
    array_name[$count]=$count
    echo '-----------'$count

   done
echo 'array_name == '${#array_name[@]}

for index in ${array_name[@]}
do
  echo '------array_name nums---'$index
done
