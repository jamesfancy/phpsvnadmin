<?php
$htpasswd = escapeshellcmd('F:\_James\Downloads\htpasswd.exe');
$cmd = $htpasswd . ' -cbm ~\pass.txt james abcdef';
echo system($cmd, $r);
echo $r;
echo crypt('abcdef', base64_encode('abcdef'));
