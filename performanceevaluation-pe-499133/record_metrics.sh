#!/bin/bash
OUTPUT_FILE="/usr/games/testdir/metrics.txt"
echo "Load Average (1m), %CPU(s)" > $OUTPUT_FILE

while true; do
  LOAD_AVG=$(uptime | awk -F 'load average: ' '{print $2}')
  CPU_PERCENT=$(ps -e -o pcpu --sort=-pcpu | head -n 2 | tail -n 1)
  echo "$LOAD_AVG, $CPU_PERCENT" >> $OUTPUT_FILE
  sleep 1
done
