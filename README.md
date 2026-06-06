# examples

다양한 예제 코드를 모아둔 저장소입니다.

## Overview

이 저장소는 작은 기능 단위의 예제 코드를 보관하기 위한 공간입니다. 현재는 PHP로 작성된 URL shortener 예제가 포함되어 있으며, 짧은 URL 생성 API와 생성된 slug를 목적지 URL로 리다이렉트하는 흐름을 보여줍니다.

## Repository Structure

```text
.
└── url_shortener/
    ├── redirect/
    │   ├── .htaccess
    │   └── index.php
    └── www/
        ├── .htaccess
        └── index.php
```

- `url_shortener/www/index.php`: `POST /api/v1/create` 요청을 받아 짧은 URL slug를 생성하는 예제 API입니다.
- `url_shortener/www/.htaccess`: `www` 예제의 Apache rewrite 설정입니다.
- `url_shortener/redirect/index.php`: 요청 경로의 slug를 조회해 목적지 URL로 `302` 리다이렉트하는 예제입니다.
- `url_shortener/redirect/.htaccess`: `redirect` 예제의 Apache rewrite 설정입니다.

## How to Use

각 예제 디렉터리는 독립적인 PHP 진입점과 Apache rewrite 설정을 포함합니다. PHP가 실행 가능한 웹 서버에서 `url_shortener/www` 또는 `url_shortener/redirect` 디렉터리를 document root로 설정한 뒤 동작을 확인할 수 있습니다.

짧은 URL 생성 API 예시:

```sh
curl -X POST http://localhost/api/v1/create \
  -d "destination_url=http://iamted.kim"
```

사용자 지정 slug를 함께 전달할 수도 있습니다.

```sh
curl -X POST http://localhost/api/v1/create \
  -d "destination_url=http://iamted.kim" \
  -d "slug=my-link"
```

리다이렉트 예제는 요청 경로를 slug로 해석합니다. 현재 mock 데이터에는 `2bI` slug가 포함되어 있습니다.

```sh
curl -i http://localhost/2bI
```

## Requirements

- PHP 실행 환경
- Apache HTTP Server 및 `mod_rewrite` 사용 환경

현재 코드는 예제용 `RedisMock` 클래스를 사용하므로 별도 Redis 서버는 필요하지 않습니다.

## Contributing

새 예제를 추가할 때는 예제별 디렉터리를 만들고, 실행 방법과 필요한 런타임을 함께 문서화해 주세요. 기존 예제의 동작을 바꾸는 경우에는 변경 의도와 확인 방법을 PR에 함께 남겨 주세요.
