<?php
function print_response($response) {
    header("Content-Type: application/json");
    echo json_encode($response);
}
if ($_SERVER["REQUEST_URI"] == "/api/v1/create" && $_SERVER["REQUEST_METHOD"] == "POST") {
    class RedisMock {
        private $shorten_index = 9999;
        function set($key, $value) {
            if ($key == "shorten_index") {
                $this->shorten_index = $value;
            }
        }
        function get($key) {
            if ($key == "shorten_index") {
                return $this->shorten_index;
            }
            return null;
        }
    }
    function base_conversion($number) {
        $base = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $result = "";
        while ($number > 0) {
            $result = $base[$number % 62].$result;
            $number = intval($number / 62);
        }
        return $result;
    }
    function generate_slug() {
        $redis = new RedisMock();
        $shorten_index = $redis->get("shorten_index") + 1;
        $slug = null;
        while (true) {
            $slug = base_conversion($shorten_index);
            if ($redis->get("url_redirect:{$slug}") == null) {
                break;
            }
            $shorten_index += 1;
        }
        $redis->set("shorten_index", $shorten_index);
        return $slug;
    }
    function save_data($destination_url, $slug) {
        $redis = new RedisMock();
        $redis->set("url_shorten:{$destination_url}", $slug);
        $redis->set("url_redirect:{$slug}", $destination_url);
    }
    $destination_url = @$_POST["destination_url"];
    if (empty($destination_url)) {
        print_response(array(
            "result_code" => 400,
            "result_msg" => "Request must have a destination_url"
        ));
        return;
    }
    $redis = new RedisMock();
    $slug = @$_POST["slug"];
    if (empty($slug)) {
        $slug = $redis->get("url_shorten:{$destination_url}");
        if (empty($slug)) {
            $slug = generate_slug();
        }
    } else if (strlen($slug) < 3 || strlen($slug) > 20) {
        print_response(array(
            "result_code" => 400,
            "result_msg" => "Slug must be between 3 and 20"
        ));
        return;
    } else {
        if ($redis->get("url_redirect:{$slug}") != null) {
            print_response(array(
                "result_code" => 400,
                "result_msg" => "Your slug already exists"
            ));
            return;
        }
    }
    save_data($destination_url, $slug);

    $short_url_domain = "https://so.me";
    print_response(array(
        "result_code" => 1, 
        "destination_url" => $destination_url,
        "slug" => $slug,
        "short_url" => $short_url_domain."/".$slug
    ));
} else {
    print_response(array("result_code" => 404));
}
?>