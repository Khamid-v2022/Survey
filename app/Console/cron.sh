#!/bin/sh
php /home/u965508571/domains/solvware.online/public_html/coachingsupport/artisan schedule:run 1>> /dev/null 2>&1

* * * * * cd /var/www/html/cronjob && php artisan schedule:run >> /dev/null 2>&1


cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php artisan schedule:run >> /dev/null 2>&1

# u965508571.solvware.online/public_html/coachingsupport