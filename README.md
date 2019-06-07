# Botus Historicus

A simple Slack bot.

## How it works

1. Provide a link to a GitHub PR.
2. Get a set of historical facts from the year that corresponds with the PR's number.
3. Enjoy history while waiting for code review.

**But seriously, how does it work?**

When you trigger the bot with a specified command and provide a link to a GitHub pull request, the bot will extract the number of the PR. Then, it will ask Wikipedia API for some info on the year of the PR's number, ~randomly~ carefully select a set of information (one from each section), and present it in a pleasant manner.

Of course it works best for rather new repos (although, surprisingly, there are some Wikipedia entries on future years).



## Installing and setting up in Slack

In order to use this bot, you will need to be able to host it somewhere, so that you can provide a URL in Slack.

1. Log in to Slack, go to https://api.slack.com/apps and click *Create an App*.
2. Name the app (e.g. *Botus Historicus*) and choose the workspace.
3. Choose _Slash commands_ and _Create new command_ on the next screen.
4. Provide required info:
  - *Command* - the slash command that will precede links to PRs
  - *Request URL* - where your bot is available (e.g. *https://yourdomain.com/slack/botus-historicus*)
  - *Short description* - that's what people will see in Slack (e.g. *Learn history while waiting for code review.*)
  - *Usage hint* - help on parameters that can be passed (e.g. *[message + link]*).
5. Save changes.
6. Go to *Settings->Basic information* of your app and click *Install App to Workspace* - when you succeed, you'll be able to see the slash command in Slack.
7. Scroll down to the *App Credentials* section. Retrieve the *Signing Secret* value - you'll need it to confgure the bot.



## Configuring

Clone the repo to a desired location and open the `histobot/config.php` file.

Now you need to provide some info for the bot to work.

- `SIGNING_SECRET` - the value you found in point 7 above (remember to update it every time you regenerate it)
- `BOT_DIR_URL` - a URL to the bot's directory; note that there's no slash nor filename at the end
- `GITHUB_ORGANIZATION` - the name of your organization that appears in your PR links (*https://github.com/your-organization/your-repo/pull/1234*)
- `$githubRepos` - an array of whitelisted repo names (as seen in PR links); if a repo name is not here, the bot will not work for a PR


### Other settings

#### Short version
You can add a second Slack command that will generate a much shorter piece of information. After adding it in Slack, remember to set up the command as `SLACK_COMMAND_SHORT` (including the preceding slash).

#### Bot name
You can define the bot's name that is displayed in the generated Slack message. Just alter tne `BOT_NAME` value.

#### Language versions
This bot was originally made in Polish, but there's also a simple English version. You can change between them by setting the `LANG` value in the config. If you wish to add more language versions, simply copy a language file in the `histobot/i18n` directory, provide relevant values for all the keys, and set the language setting that corresponds with the newly created file name.

#### Local testing
If you wish to test the bot without actually connecting it with Slack or you'd like such an option do developing purposes, you can set the `LOCAL_TESTING` value to `true`, and `LOCAL_POST_TEXT` as a demo link to a PR. You can then simply run `php index.php` (or trigger the script however you like) to get a JSON response. You can also alter the `LOCAL_POST_COMMAND` value if you wish to test the `SLACK_COMMAND_SHORT` version.



---
Note: All images were taken from Wikipedia pages (Public Domain or CC BY-SA 4.0).
