<?php
class CONFIG
{
    /** Bitbucket user, used to authenticate. */
    CONST BITBUCKET_USER = 'BigBangBusiness';

    /** Bitbucket user password, used to authenticate. */
    CONST BITBUCKET_PASSWORD = 'yd8ajd82hd';

    /** Bitbucket account that holds the repositories. Most of time it's the same as BITBUCKET_USER. */
    CONST BITBUCKET_ACCOUNT = 'BigBangBusiness';

    /** Name of the webhook to be added. */
    CONST WEBHOOK_NAME = 'Webhook Example';

    /** Url of the webhook to be added. */
    CONST WEBHOOK_URL = 'http://example.com/webhook/B0CKKFJMA';

    /** Whether to delete all webhooks of each repository before adding this one. */
    CONST DELETE_ALL_PREVIOUS_WEBHOOKS = false;
}

require_once __DIR__.'/vendor/autoload.php';

$bb_user = CONFIG::BITBUCKET_USER;
$bb_pass = CONFIG::BITBUCKET_PASSWORD;
$teamname = CONFIG::BITBUCKET_ACCOUNT; // If User == Account/Team, just set teamname = bb_user here.

header("Content-Type: text/plain");

$repositories = new Bitbucket\API\Repositories();
$repositories->setCredentials( new Bitbucket\API\Authentication\Basic($bb_user, $bb_pass) );

$repos = $repositories->all($teamname);

$reposArray = $repos;

$slugsArray = array();

if (isset($reposArray['values']))
{
    foreach($reposArray['values'] as $repo)
    {
        array_push($slugsArray, strtolower($repo['name']));
    }
}

$webhooks  = new Bitbucket\API\Repositories\Hooks();

$webhooks->setCredentials( new Bitbucket\API\Authentication\Basic($bb_user, $bb_pass) );

echo "Adding webhook " . CONFIG::WEBHOOK_NAME . " to " . count($slugsArray) . " repositories from " . $teamname . ":" . PHP_EOL;

foreach ($slugsArray as $slug)
{
    $webhookAlreadyAdded = false;

    $repositoryWebhooks = json_decode($webhooks->all($teamname, $slug)->getContent());
    if (isset($repositoryWebhooks->values))
    {
        if (CONFIG::DELETE_ALL_PREVIOUS_WEBHOOKS)
        {
            foreach($repositoryWebhooks->values as $currentWebhook)
            {
                $webhooks->delete($teamname, $slug, $currentWebhook->uuid);
                echo "[Webhook Deleted] $currentWebhook->description ($currentWebhook->url) from " . $teamname . "/$slug". PHP_EOL;
            }
        }
        else
        {
            foreach($repositoryWebhooks->values as $currentWebhook)
            {
                if ($currentWebhook->description == CONFIG::WEBHOOK_NAME && $currentWebhook->url == CONFIG::WEBHOOK_URL && $currentWebhook->active)
                {
                    $webhookAlreadyAdded = true;
                    echo "[Not Needed] Webhook already installed on " . $teamname . "/$slug" . PHP_EOL;
                    break;
                }
            }
        }
    }

    if (!$webhookAlreadyAdded)
    {
        $webhooks->create($teamname, $slug, array(
            'description' => CONFIG::WEBHOOK_NAME,
            'url' => CONFIG::WEBHOOK_URL,
            'active' => true,
            'events' => array(
                'repo:push',
            )
        ));
        echo "[OK] " . $teamname . "/$slug" . PHP_EOL;
    }
}

echo PHP_EOL . "Done! The webook is now included on " . count($slugsArray) . " repositories. Have a great day!" . PHP_EOL;
