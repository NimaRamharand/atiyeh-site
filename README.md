# سایت آتیه آمار پژوهان پارسیان (وردپرس لوکال)

> راهنمای کامل و چک‌لیست جلسه دمو در پایان فاز ۵ اضافه می‌شود.

## اجرا (Docker)
```bash
npm install
npm run start        # http://localhost:8093  — مدیریت: admin / atiyeh-demo
npm run reset-demo   # پاک کردن و ساخت دوباره محتوای نمونه
npm run stop
```

## اجرا بدون Docker (حالت سبک، فقط برای توسعه)
نیاز به PHP 8.2+ با `pdo_sqlite` دارد (لینوکس/مک):
```bash
npm run start:lite   # http://localhost:8081 — SQLite به‌جای MariaDB
```
