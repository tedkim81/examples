<?php
class RedisMock {
    function get($key) {
        if ($key == "url_redirect:2bI") {
            return "http://iamted.kim";
        }
        return null;
    }
}
if (empty($_SERVER["REQUEST_URI"]) || $_SERVER["REQUEST_URI"][0] != "/" || strlen($_SERVER["REQUEST_URI"]) < 2) {
	http_response_code(404);
	echo "404 Not Found";
	return;
}
$slug = substr($_SERVER["REQUEST_URI"], 1);
$redis = new RedisMock();
$destination_url = $redis->get("url_redirect:{$slug}");
if (!empty($destination_url)) {
	header("Location: {$destination_url}", true, 302);
} else {
	http_response_code(404);
	echo "404 Not Found";
}
?>