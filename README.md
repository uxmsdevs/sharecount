# Share Count

This plugin can obtain sharing count of an URL on Facebook/Twitter/Google+ Platforms

## Settings

> **Note:** We suggest you to select minimum "Daily" as Cache Timeout for performance matters. Also do not forget to select right timezone for right results.

## Component

Share Count plugin can get sharing count of a webpage on Facebook/Twitter/Google+

You just need to add `Share Count` Component to a page which you need to show counts on.

> **Note:** Please do not forget to select a webpage in "Webpage to Show Count" dropdown.

## How to show counts?
Use "ComponentName".facebook | twitter | googleplus for show specific sharing counts.

For showing **Facebook** count of an URL:

    {{ shareCount.facebook }}

For showing **Twitter** count of an URL:

    {{ shareCount.twitter }}

For showing **Google Plus** count of an URL:

    {{ shareCount.googleplus }}
