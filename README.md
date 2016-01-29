# Self-Service Slack Signup (in PHP)

There are many doodads out there that allow the general public to invite themselves to your slack.com instance. After surveying the range of options, I decided to write one of my own since many of the existing ones lacked input validation or did other silly things.

NB: ANYONE with the URL can create invitations. Think and use accordingly.

## License:

Released to the public domain with no warranty whatsoever. All rights reversed, all wrongs forgiven. 

## Requirements:

* A webserver with PHP5 and PHP_CURL support.
* The auth token from an ADMIN (not owner) on your Slack.com instance.

## Installation:

* Create a directory on your webserver.
* Dump call-me-index.php in there as index.php (of course)
* Edit the site-specific variables at the top, or (if you prefer) set them in a separate file ('vars.php' in the example)
* Optionally, add a background graphic (bg.jpg is defined in the CSS already).
