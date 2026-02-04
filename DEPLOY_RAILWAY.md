# Deploying QuizWebsite to Railway (Free)

This checklist gets your Laravel app live on Railway in ~5–8 minutes.

## 1) Prerequisites
- GitHub repo pushed (done)
- Railway account: https://railway.app

## 2) Create a Railway project
1. New Project → Deploy from GitHub → select `QuizWebsite`.
2. Railway will create a Service for your app (builds with Nixpacks automatically).

## 3) Add a database (MySQL or Postgres)
- Project → Add Plugin → choose MySQL (or Postgres).
- Railway exposes env vars to your project (e.g., `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`).

Map them to Laravel in your app Service → Variables:
```
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}
```

## 4) App environment variables
In the app Service → Variables, add:
```
APP_ENV=production
APP_KEY=base64:paste_the_value_from_local
APP_DEBUG=false
APP_URL=https://<your-app>.up.railway.app
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
CACHE_STORE=file

# AI (Groq)
AI_PROVIDER=groq
GROQ_API_KEY=your_groq_api_key
AI_GROQ_MODEL=llama-3.3-70b-versatile
```
Generate APP_KEY locally and paste:
```
php artisan key:generate --show
```

## 5) Build & Start commands
Open the app Service → Settings → Deploy → Override commands.

Build commands (run in this order):
```
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Start command (exposes HTTP on Railway’s `$PORT`):
```
php artisan migrate --force; php artisan serve --host 0.0.0.0 --port $PORT
```

After deploy, open the Service URL and verify the app loads.

---

## 6) WebSockets (Laravel Reverb)
You have two options:

### Option A: Temporarily disable Reverb in production (simplest)
- In `config/broadcasting.php`, set default to `null` in production via env (or ensure features using broadcasting are optional).
- Skip the Reverb steps below. You can add WebSockets later.

### Option B: Run Reverb as a second Service on Railway (recommended)
Run one Service for HTTP (the app) and a second Service just for Reverb.

1) Create another Service from the same repo: Project → New → Deploy from GitHub → pick `QuizWebsite` again, name it `reverb`.

2) Reverb Service → Variables:
```
APP_ENV=production
APP_KEY=base64:same_as_app_service
APP_DEBUG=false

REVERB_APP_ID=928917
REVERB_APP_KEY=wkbciurpftvzpbpwbrjw
REVERB_APP_SECRET=hhwlvi4rsfpshkdjn4l8
REVERB_HOST=0.0.0.0
REVERB_PORT=$PORT
REVERB_SCHEME=https
```
Note: Railway terminates TLS at the edge, so clients should connect with `wss` to the Reverb service domain.

3) Reverb Service → Commands
- Build commands: (same Composer step only is fine)
```
composer install --no-dev --optimize-autoloader
```
- Start command (listen on `$PORT`):
```
php artisan reverb:start --host 0.0.0.0 --port $PORT
```

4) Frontend client env (already present):
```
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="<reverb-service-subdomain>.up.railway.app"
VITE_REVERB_PORT="443"
VITE_REVERB_SCHEME="wss"
```
Set these in the app Service Variables so the built JS points to the Reverb service domain. Rebuild the app Service after changing these (trigger a redeploy).

5) In `config/broadcasting.php`/`config/reverb.php`, ensure `.env` is used (already set in this repo). No code changes needed.

---

## 7) Files & uploads
Railway’s filesystem is ephemeral. For persistent uploads later, switch to S3-compatible storage (e.g., Cloudflare R2 free tier) and set `FILESYSTEM_DISK=s3` with appropriate credentials. For now, keep uploads light.

## 8) Post-deploy checklist
- Open the app URL → login → navigate to Edit Quiz → test AI chat.
- If AI fails, set `GROQ_API_KEY` in Variables and redeploy.
- If WebSockets fail:
  - Confirm Reverb Service is healthy and `VITE_REVERB_*` point to its domain.
  - Ensure `wss://<reverb-service>.up.railway.app` is reachable from the browser.

## 9) Useful commands
Redeploy after changing Variables:
```
# touch a file to trigger build or click "Deploy"
```
View logs:
- App Service → Logs
- Reverb Service → Logs

## 10) Troubleshooting
- 502 on first load: make sure Start command uses `$PORT`.
- APP_KEY missing: paste `php artisan key:generate --show` output into APP_KEY.
- Database errors: verify DB_* values map to Railway plugin variables.
- WebSockets not connecting: verify `wss://` URL, port 443, and that you rebuilt the app after changing VITE vars.
