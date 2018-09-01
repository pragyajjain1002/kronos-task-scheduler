#!/bin/bash
cd /home/akash/Desktop/temp2/CS252-Radicals/Assignment\ 1/images
for fn in `ls|grep .png`; do
  ffmpeg -i $fn -s 125x100 img/$fn
done
