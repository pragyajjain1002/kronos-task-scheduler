#!/bin/bash
cd /home/akash/Desktop/temp2/CS252-Radicals/Assignment\ 1/images/img
for fn in `ls|grep .png`; do
  base64 $fn> $fn.dat
done
