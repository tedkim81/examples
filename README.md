# tedkim81/examples

예제 코드를 모아 둔 저장소입니다.

## 포함된 예제

- `url_shortener/`: PHP로 작성된 간단한 URL 단축기 예제
  - `www/`: 단축 URL 생성 API
  - `redirect/`: 단축 slug를 원본 URL로 리다이렉트하는 엔드포인트

## 실행 예시

PHP 내장 서버로 각 예제를 실행할 수 있습니다.

```bash
php -S localhost:8000 -t url_shortener/www
```

다른 터미널에서 단축 URL 생성 API를 호출합니다.

```bash
curl -X POST http://localhost:8000/api/v1/create \
  -d "destination_url=http://iamted.kim"
```

리다이렉트 예제는 별도 포트에서 실행할 수 있습니다.

```bash
php -S localhost:8001 -t url_shortener/redirect
```

## 참고

현재 예제는 `RedisMock` 클래스를 사용하므로 실제 Redis 서버 없이 동작을 확인할 수 있습니다.
