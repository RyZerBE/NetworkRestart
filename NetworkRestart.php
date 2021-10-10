<?php

const STOP_SCRIPT = [
    "screen -S RyZerCloud -X stuff 'group stop all\n'",
    "screen -S Bungee -X stuff 'end\n'",
];
const RESTART_SCRIPT = [
    "screen -S RyZerCloud -X stuff 'group start all\n'",
    "cd /root/WaterdogPE/ && screen -AmdS Bungee java -jar WaterdogPE.jar",
];

$restartTime = "04:00";
$restarted = false;

echo "Starting loop...\n";
echo "Current Time: " . date("H:i") . "\n";

while(true) {
    sleep(5);

    $date = date("H:i");
    if($date === $restartTime) {
        if($restarted) continue;

        $microtime = microtime(true);

        echo "Restarting Network...\n";
        foreach(STOP_SCRIPT as $script) popen($script, "r");
        sleep(5);
        foreach(RESTART_SCRIPT as $script) passthru($script);
        echo "Done! Took " . round(microtime(true) - $microtime, 2) . "s\n";

        $restarted = true;
        continue;
    }
    $restarted = false;
}