<?php

/**
 * Library Requirements
 *
 * 1. Install composer (https://getcomposer.org)
 * 2. On the command line, change to this directory (api-samples/php)
 * 3. Require the google/apiclient library
 *    $ composer require google/apiclient:~2.0
 */
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/vendor/autoload.php';

// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if (isset($_GET['q']) && isset($_GET['maxResults'])) {
  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
   * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
   * Please ensure that you have enabled the YouTube Data API for your project.
   */
  $DEVELOPER_KEY = 'вставить сюда ваш гугл ключ';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  $htmlBody = '';
  try {

    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $_GET['q'],
      'maxResults' => $_GET['maxResults'],
    ));

    $videos = '';
    $channels = '';
    $playlists = '';

$xml= '<?xml version="1.0" encoding="UTF-8"?><clips>';

    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
				 // $searchResult['snippet']['title'];
				// echo 'https://www.youtube.com/watch?v='.$searchResult['id']['videoId'];
				
					
					$xml.= '<clip><title>'.$searchResult['snippet']['title'].'</title>';
					$xml.= '<videoId>'.$searchResult['id']['videoId'].'</videoId></clip>';
					
				 
          break;
        case 'youtube#channel':
        //  $channels .= sprintf('<li>%s (%s)</li>',
         //     $searchResult['snippet']['title'], $searchResult['id']['channelId']);
          break;
        case 'youtube#playlist':
        //  $playlists .= sprintf('<li>%s (%s)</li>',
        //      $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
          break;
      }
    }
	$xml.= '</clips>';
echo $xml;

  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}
?>
