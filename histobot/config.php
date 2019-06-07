<?php

require_once __DIR__ . '/core/autoloader.php';

class Config extends HistoBot\Core\AbstractConfig
{
    /**
     * @var string Signing Secret from your Slack app config page
     */
    const SIGNING_SECRET = '5l4ck93n3r4t3d519n1n953cr3th45h!';

    /**
     * @var string Absolute URL to the bot (not slash, not file name)
     */
    const BOT_DIR_URL = 'https://yourdomain.com/slack/botus-historicus';

    /**
     * @var string Your GitHub organization name
     */
    const GITHUB_ORGANIZATION = 'test';

    /**
     * @var array Whitelist of allowed repository names
     */
    protected static $githubRepos = [
        'test-repo',
        'testing',
    ];



    /** -----------------
     * OPTIONAL SETTINGS:
     * ------------------ */

    /**
     * @var string Language version, lang-region format, eg. 'en-US' or 'pl-PL'
     */
    const LANG = 'pl-PL';

    /**
     * @var string Command to display a selected date from only one section
     */
    const SLACK_COMMAND_SHORT = '/histpr';

    /**
     * @var string Bot's name (seen in Slack)
     */
    const BOT_NAME = 'Botus Historicus Maximus';



    /** -------
     * TESTING:
     * -------- */

    /**
     * @var bool True = local script (testing); False = functioning bot (prod)
     */
    const LOCAL_TESTING = true;

    /**
     * @var string Fake $_POST['text'] value, used when LOCAL_TESTING === true
     */
    const LOCAL_POST_TEXT = 'https://github.com/test/testng/pull/1234';

    /**
     * @var string Fake $_POST['command'] value, used when LOCAL_TESTING === true
     */
    const LOCAL_POST_COMMAND = '/historicpr';
}
