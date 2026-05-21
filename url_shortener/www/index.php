<?php
function print_response($response) {
    header("Content-Type: application/json");
    echo json_encode($response);
}
if (($_SERVER["REQUEST_URI"] == "/" || $_SERVER["REQUEST_URI"] == "/calculator") && $_SERVER["REQUEST_METHOD"] == "GET") {
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calculator</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background: #f4f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .calculator {
            width: min(92vw, 340px);
            padding: 20px;
            border: 1px solid #d9e1ec;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 18px 45px rgba(31, 41, 55, 0.12);
        }

        .display {
            width: 100%;
            height: 70px;
            margin-bottom: 14px;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #0f172a;
            color: #ffffff;
            font-size: 32px;
            text-align: right;
        }

        .keys {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        button {
            height: 56px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #ffffff;
            color: #1f2937;
            font-size: 20px;
            cursor: pointer;
        }

        button:hover {
            background: #eef2f7;
        }

        .operator,
        .equals {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .operator:hover,
        .equals:hover {
            background: #1d4ed8;
        }

        .clear {
            background: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
        }

        .clear:hover {
            background: #dc2626;
        }

        .zero {
            grid-column: span 2;
        }
    </style>
</head>
<body>
    <main class="calculator" aria-label="계산기">
        <input class="display" id="display" value="0" aria-label="계산 결과" readonly>
        <div class="keys">
            <button type="button" class="clear" data-action="clear">C</button>
            <button type="button" data-action="delete">DEL</button>
            <button type="button" data-value="%">%</button>
            <button type="button" class="operator" data-value="/">/</button>
            <button type="button" data-value="7">7</button>
            <button type="button" data-value="8">8</button>
            <button type="button" data-value="9">9</button>
            <button type="button" class="operator" data-value="*">x</button>
            <button type="button" data-value="4">4</button>
            <button type="button" data-value="5">5</button>
            <button type="button" data-value="6">6</button>
            <button type="button" class="operator" data-value="-">-</button>
            <button type="button" data-value="1">1</button>
            <button type="button" data-value="2">2</button>
            <button type="button" data-value="3">3</button>
            <button type="button" class="operator" data-value="+">+</button>
            <button type="button" class="zero" data-value="0">0</button>
            <button type="button" data-value=".">.</button>
            <button type="button" class="equals" data-action="calculate">=</button>
        </div>
    </main>
    <script>
        const display = document.getElementById("display");
        const keys = document.querySelector(".keys");
        const operators = ["+", "-", "*", "/", "%"];
        let expression = "";

        function render(value) {
            display.value = value || "0";
        }

        function appendValue(value) {
            const last = expression.slice(-1);

            if (operators.includes(value) && (expression === "" || operators.includes(last))) {
                expression = expression.slice(0, -1) + value;
                render(expression);
                return;
            }

            if (value === "." && expression.split(/[+\-*/%]/).pop().includes(".")) {
                return;
            }

            expression += value;
            render(expression);
        }

        function calculate() {
            if (!expression || operators.includes(expression.slice(-1))) {
                return;
            }

            try {
                const result = Function('"use strict"; return (' + expression + ')')();
                expression = Number.isFinite(result) ? String(Math.round(result * 100000000) / 100000000) : "";
                render(expression || "Error");
            } catch (error) {
                expression = "";
                render("Error");
            }
        }

        keys.addEventListener("click", function (event) {
            const button = event.target.closest("button");

            if (!button) {
                return;
            }

            if (button.dataset.action === "clear") {
                expression = "";
                render(expression);
                return;
            }

            if (button.dataset.action === "delete") {
                expression = expression.slice(0, -1);
                render(expression);
                return;
            }

            if (button.dataset.action === "calculate") {
                calculate();
                return;
            }

            appendValue(button.dataset.value);
        });
    </script>
</body>
</html>
<?php
} else if ($_SERVER["REQUEST_URI"] == "/api/v1/create" && $_SERVER["REQUEST_METHOD"] == "POST") {
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
