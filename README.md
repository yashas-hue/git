KARMAPRENEUR.IN

Stack: PHP (>=8.1), MySQL, Bootstrap 5.

Setup

1) Create database and import schema:

```bash
mysql -u root -p -e "CREATE DATABASE karmapreneur CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p karmapreneur < database/schema.sql
```

2) Configure environment variables (SpeedHost/Apache):

- APP_ENV=production
- APP_DEBUG=0
- APP_URL=https://karmapreneur.in
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_NAME=karmapreneur
- DB_USER=...
- DB_PASS=...
- MERCHANT_VPA=merchant@upi
- MEDIA_TOKEN_SECRET=change-me
- CSRF_SECRET=change-me

3) Deploy public as webroot. Ensure /uploads is non-public or tokenized.

Notes

- Referral required to purchase; admin can bypass by setting is_admin=1 in users.
- Media token endpoint returns a stub HLS URL; integrate with actual HLS origin and DRM as needed.
- Add SMTP and Slack/WhatsApp integrations later where needed.

Scripts

No build step. Optionally run `composer dump-autoload` for PSR-4.
# html-portfolio