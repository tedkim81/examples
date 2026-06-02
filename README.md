# URL Shortener

간단한 PHP 기반 URL 단축 서비스 예제입니다. 긴 URL을 짧은 slug로 변환하는 생성 API와, 생성된 slug로 접속했을 때 원본 URL로 리다이렉트하는 기능을 포함합니다.

## 설치 방법

1. PHP와 Apache 웹 서버를 설치합니다.
2. `mod_rewrite`가 활성화되어 있는지 확인합니다.
3. 웹 서버 문서 루트를 다음 디렉터리 중 용도에 맞게 설정합니다.
   - `url_shortener/www`: 단축 URL 생성 API
   - `url_shortener/redirect`: 단축 URL 리다이렉트
4. Apache 설정에서 `.htaccess` 사용이 가능하도록 `AllowOverride`를 활성화합니다.

## 사용법

### 단축 URL 생성

`url_shortener/www`를 문서 루트로 실행한 뒤 다음과 같이 요청합니다.

```bash
curl -X POST http://localhost/api/v1/create \
  -d "destination_url=https://example.com"
```

원하는 slug를 직접 지정하려면 `slug` 값을 함께 전달합니다.

```bash
curl -X POST http://localhost/api/v1/create \
  -d "destination_url=https://example.com" \
  -d "slug=my-link"
```

### 단축 URL 접속

`url_shortener/redirect`를 문서 루트로 실행한 뒤 브라우저에서 다음 형식으로 접속합니다.

```text
http://localhost/{slug}
```

현재 코드는 저장소 없이 동작을 설명하기 위한 `RedisMock`을 사용합니다. 실제 서비스로 운영하려면 Redis 등 영구 저장소 연결을 추가해야 합니다.
