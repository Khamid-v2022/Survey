#!/bin/sh
cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php script2.php cron:run > /dev/null 2>&1
# cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php artisan schedule:run >> /dev/null 2>&1