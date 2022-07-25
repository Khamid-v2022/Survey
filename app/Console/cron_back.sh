#!/bin/sh
/usr/bin/php /home/u965508571/domains/solvware.online/public_html/coachingsupport/script.php cron:run > /dev/null 2>&1

cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php artisan schedule:run >> /dev/null 2>&1

/usr/bin/php /home/u965508571/domains/solvware.online/public_html/coachingsupport/artisan schedule:run >> /dev/null 2>&1