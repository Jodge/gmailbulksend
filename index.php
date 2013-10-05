<?php

// client id
$client_id = 'your google client id';

// redirect Url
$redirectUri = 'your redirect Uri should match the one registered in the api console';
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Gmail - Send Mail</title>
   </head>
<body>
     <a href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $client_id; ?>&redirect_uri=<?php echo $redirectUri; ?>&scope=https://www.google.com/m8/feeds/&response_type=code">Invite Friends</a>
</body>
</html>
