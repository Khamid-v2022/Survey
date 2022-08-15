#!/bin/sh
cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php script.php cron:run > /home/u965508571/domains/solvware.online/public_html/coachingsupport/app/debug.txt 2>&1
#env >> /home/u965508571/domains/solvware.online/public_html/coachingsupport/app/env.txt
#command -v php >> /home/u965508571/domains/solvware.online/public_html/coachingsupport/app/php.txt
#cd /home/u965508571/domains/solvware.online/public_html/coachingsupport && php artisan schedule:run >> /home/u965508571/domains/solvware.online/public_html/coachingsupport/app/debug.txt 2>&1