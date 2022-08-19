<?php
$intervalSecond = 20;

echo "Started sshd status logging program.\n";
echo "process interval ".$intervalSecond." seconds.\n\n";

while(true){
     echo date('Y/m/d H:i:s')." process started. \n";

    $yesterdaySocketStatusLogPath = exec('echo -e "/var/log/socket-status-`date "+%Y%m%d" --date \'1 day ago\'`.log"');
    $yesterdayFail2banStatusLogPath = exec('echo -e "/var/log/fail2ban-status-`date "+%Y%m%d" --date \'1 day ago\'`.log"');

    if(file_exists($yesterdaySocketStatusLogPath))
    {
        $data = file_get_contents($yesterdaySocketStatusLogPath);
        $gzdata = gzencode($data, 9);
        file_put_contents($yesterdaySocketStatusLogPath.".gz", $gzdata);
        unlink($yesterdaySocketStatusLogPath);
    };

    if(file_exists($yesterdayFail2banStatusLogPath))
    {
        $data = file_get_contents($yesterdayFail2banStatusLogPath);
        $gzdata = gzencode($data, 9);
        file_put_contents($yesterdayFail2banStatusLogPath.".gz", $gzdata);
        unlink($yesterdayFail2banStatusLogPath);
    };

    echo exec('echo "[`date`]" >> /var/log/socket-status-`date "+%Y%m%d"`.log && ss -an | grep :22 >> /var/log/socket-status-`date "+%Y%m%d"`.log');
    echo exec('echo "[`date`]" >> /var/log/fail2ban-status-`date "+%Y%m%d"`.log && fail2ban-client status sshd >> /var/log/fail2ban-status-`date "+%Y%m%d"`.log');

    echo date('Y/m/d H:i:s')." processed. \n";

    sleep($intervalSecond);

	//test change
}