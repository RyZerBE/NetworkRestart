<?php

const STOP_SCRIPT = [
    "screen -S RyZerCloud -X stuff 'group stop all\n'",
    "screen -S Bungee -X stuff 'end\n'",
];
const KILL_SCRIPTS = [
    "screen -XS Bungee kill"
];
const RESTART_SCRIPT = [
    "screen -S RyZerCloud -X stuff 'group start all\n'",
    "cd /root/WaterdogPE/ && screen -AmdS Bungee java -jar WaterdogPE.jar",
];

$restartTime = "04:00";
$restarted = false;

echo str_repeat("\n", 100);
info("Loop started! Use /help for a list of commands");
info("Current Time: " . date("H:i"));

while(true) {
    sleep(1);

    checkInput();

    $date = date("H:i");
    if($date === $restartTime) {
        if($restarted) continue;
        restart();
        $restarted = true;
        continue;
    }
    $restarted = false;
}

function checkInput(): void {
    $fh = @fopen("php://stdin", "r");
    if($fh === false) return;
    stream_set_blocking($fh, false);
    $get = fgets($fh);
    if($get === false) return;
    $userInput = trim($get);
    fclose($fh);
    if(empty($userInput) || !str_starts_with($userInput, "/")) return;
    $userInput = substr($userInput, 1);

    switch($userInput) {
        case "restart": {
            restart();
            break;
        }
        case "time": {
            info("Current Time: " . date("H:i"));
            break;
        }
        case "stop": {
            exit();
        }
        case "help": {
            $commandList = [
                "restart",
                "time",
                "help",
                "stop",
            ];
            foreach($commandList as $str) {
                info("» /" . $str);
            }
            break;
        }
    }
}

function restart(): void {
    $microtime = microtime(true);
    info("Restarting Network...");
    foreach(STOP_SCRIPT as $script) popen($script, "r");
    sleep(5);
    foreach(KILL_SCRIPTS as $script) popen($script, "r");
    sleep(5);
    foreach(RESTART_SCRIPT as $script) passthru($script);
    info("Done! Took " . round(microtime(true) - $microtime, 2) . "s");
}

function info(string $log): void{
    echo "[" . date("H:i") . "] " . $log . "\n";
}