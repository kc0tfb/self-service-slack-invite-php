# self-service-slack-signup-php

There are many self-service doodads in various languages to allow people to sign up for an instance at Slack.com; this one is mine. Now it's yours, too.

Released to the public domain with no warranty whatsoever. All rights reversed, all wrongs forgiven.

Installation:
1. Put it on a webserver that supports PHP 5+, with PHP_Curl library installed
2. Create an admin user in your Slack instance and get the auth token for that user.
3. Edit the site-specific variables at the top, or (if you prefer) set them in a separate file ('vars.php' in the example)
4. Test and enjoy.
