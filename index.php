<?php
$subreddit = 'pokemon';
$max_pages = 10;

// Set variables with default data
$page = 0;
$after = '';
$titles = '';
do {
    $url = 'http://www.reddit.com/r/' . $subreddit . '/new.json?limit=25&after=' . $after;

    // Set URL you want to fetch
    $ch = curl_init($url);

    // Set curl option of of header to false (don't need them)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Set curl option of nobody to false as we need the body
    curl_setopt($ch, CURLOPT_NOBODY, 0);

    // Set curl timeout of 5 seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    // Set curl to return output as string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Execute curl
    $output = curl_exec($ch);

    // Get HTTP code of request
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close curl
    curl_close($ch);

    // If http code is 200 (success)
    if ($status == 200) {
        // Decode JSON into PHP object
        $json = json_decode($output);
        // Set after for next curl iteration (reddit's pagination)
        $after = $json->data->after;
        // Loop though each post and output title
        foreach ($json->data->children as $k => $v) {
            $titles .= $v->data->title . "\n";
        }
    }
    // Increment page number
    $page++;
// Loop though whilst current page number is less than maximum pages
} while ($page < $max_pages);

// Save titles to text file
file_put_contents(dirname(__FILE__) . '/' . $subreddit . '.txt', $titles);
?>
