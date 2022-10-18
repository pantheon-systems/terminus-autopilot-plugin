<?php

/**
 * Bootstrap file for functional tests.
 */

include_once getenv('HOME') . '/terminus/terminus/vendor/autoload.php';

$tokens_dir = implode(DIRECTORY_SEPARATOR, [$_SERVER['HOME'], '.terminus', 'cache' , 'tokens']);
if (!is_dir($tokens_dir)) {
    mkdir(
        $tokens_dir,
        0700,
        true
    );
}

$testcache_dir = implode(DIRECTORY_SEPARATOR, [$_SERVER['HOME'], '.terminus', 'testcache']);
if (!is_dir($testcache_dir)) {
    mkdir(
        $testcache_dir,
        0700,
        true
    );
}

// Override the default cache directory by setting an environment variable. This prevents our tests from overwriting
// the user's real cache and session.
putenv(sprintf('TERMINUS_CACHE_DIR=%s/.terminus/testcache', getenv('HOME')));

$token = getenv('TERMINUS_TOKEN');
if (empty($token)) {
    $dir = new DirectoryIterator($tokens_dir);
    $tokens = array_diff(scandir(
        $dir->getRealPath(),
        SCANDIR_SORT_DESCENDING
    ), ['..', '.']);
    if (count($tokens)) {
        $token = array_shift($tokens);
        $tokenData = json_decode(
            file_get_contents(
                $dir->getRealPath() . DIRECTORY_SEPARATOR . $token
            ),
            false,
            JSON_THROW_ON_ERROR
        );
        putenv(sprintf('TERMINUS_TOKEN=%s', $tokenData->token));
    }
}

const TERMINUS_BIN_FILE = 'terminus';

if ($token) {
    exec(
        sprintf(
            '%s auth:login --machine-token=%s',
            TERMINUS_BIN_FILE,
            $token
        )
    );
}
