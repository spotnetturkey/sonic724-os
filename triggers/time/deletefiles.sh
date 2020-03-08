cd /var/www/triggers/time
find . -xdev -type f -name '*_files' -print | while read filename
do
       # Remove echo when tested thoroughly
       sed -n -i  "/$1/!p" "${filename}"
done

find . -type f -size 0b -delete
