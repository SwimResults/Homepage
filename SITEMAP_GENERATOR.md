# Sitemap Generator for Docker

## Overview

The sitemap is automatically generated as part of the Docker build process:
1. **During build** (`docker build`): Creates initial sitemap from `pages.json` only (static pages)
2. **On container startup**: Regenerates sitemap with full content including blog articles from the database

This ensures the sitemap is always included in the Docker image and automatically updates when the container starts.

## How It Works

### Build Time
When building the Docker image:
```bash
docker build -t swimresults-homepage .
```

The `Dockerfile` runs:
```dockerfile
RUN php /generate-sitemap.php --static-only
```

This generates `src/sitemap.xml` with:
- All pages from `pages.json` with `nav: true` or `footer: true`
- Homepage (main page)
- **No blog articles** (database not available during build)

### Runtime (Container Startup)

When the container starts:
1. `docker-entrypoint.sh` runs:
   - Waits for database connection (if configured)
   - Calls `generate-sitemap.php` (without `--static-only`)
   - Regenerates sitemap with **full content** including blog articles
   - Starts Apache

The regenerated sitemap now includes:
- All static pages from build
- **All published blog articles** from the database

## Files

- **[generate-sitemap.php](/generate-sitemap.php)** — CLI script that generates the sitemap
  - `php generate-sitemap.php --static-only` — Build-time generation (no database)
  - `php generate-sitemap.php` — Full generation (with database articles)

- **[docker-entrypoint.sh](/docker-entrypoint.sh)** — Container startup script
  - Waits for database availability
  - Regenerates sitemap at startup
  - Starts Apache

- **[Dockerfile](/Dockerfile)** — Updated with sitemap generation
  - Installs `netcat` for database health checks
  - Generates initial sitemap during build
  - Sets entrypoint script

## Usage

### Build and Run with Docker Compose

```bash
docker-compose up --build
```

The sitemap will be automatically generated twice:
1. During the image build (`--static-only` mode)
2. When the container starts (full mode with database)

### Build and Run with Docker

```bash
# Build image
docker build -t my-swimresults .

# Run container
docker run -p 80:8080 \
  -e SR_HOMEPAGE_DB_HOST=mysql_host \
  -e SR_HOMEPAGE_DB_PORT=3306 \
  -e SR_HOMEPAGE_DB_NAME=sr_web \
  -e SR_HOMEPAGE_DB_USER=root \
  -e SR_HOMEPAGE_DB_PASSWORD=password \
  my-swimresults
```

### Manual Regeneration (Production)

To regenerate the sitemap without rebuilding the Docker image:

```bash
# Access the container
docker exec -it container_name php /generate-sitemap.php

# Or trigger via API endpoint
curl https://swimresults.de/api/sitemap
```

## Configuration

### Database Connection

The generator uses these environment variables (set in `docker-compose.yml` or `docker run`):
- `SR_HOMEPAGE_DB_HOST` — Database hostname
- `SR_HOMEPAGE_DB_PORT` — Database port (default: 3306)
- `SR_HOMEPAGE_DB_NAME` — Database name
- `SR_HOMEPAGE_DB_USER` — Database user
- `SR_HOMEPAGE_DB_PASSWORD` — Database password
- `SR_HOMEPAGE_DB_DISABLE` — Set to `true` to disable database (static pages only)

If database is unavailable, the generator continues with static pages only.

### Base URL

Automatically detected based on `SR_HOMEPAGE_ENV`:
- Production: `https://swimresults.de`
- Development/Localhost: `http://localhost:4300`

### Startup Behavior

The `docker-entrypoint.sh` script:
- Waits up to 30 seconds for database connection
- If database connects: Generates full sitemap
- If database timeout: Logs warning and generates static-only sitemap
- Always continues with Apache startup (doesn't fail if database unavailable)

## Output

When running the generator:

```bash
$ php generate-sitemap.php
[Sitemap] Generating sitemap...
[Sitemap] Base URL: https://swimresults.de
[Sitemap] Static only: no
[Sitemap] Found 12 pages in config
[Sitemap] Added 8 static pages
[Sitemap] Connecting to database...
[Sitemap] Added 15 blog articles from database
[Sitemap] ✓ Sitemap saved successfully to: /var/www/html/sitemap.xml
[Sitemap] Total URLs: 24
```

## Blog Article URL Format

Blog articles are included with URLs in the format:
```
/article/{id}-{slug}
```

Where `{slug}` is auto-generated from the article title:
- "Informationen für Veranstalter" → `informationen-für-veranstalter`
- "Eine erfolgreiche Premiere" → `eine-erfolgreiche-premiere`

Only published articles (where `published_at <= NOW()`) are included.

## Sitemap Priorities

- **Navigation pages** (`nav: true`): 1.0
- **Footer pages** (`footer: true`): 0.8
- **Blog articles**: 0.8
- **Homepage**: 1.0

## Docker Compose Example

```yaml
services:
  main:
    build:
      dockerfile: Dockerfile
    ports:
      - "4300:80"
    environment:
      SR_HOMEPAGE_ENV: "PRODUCTION"
      SR_HOMEPAGE_DB_HOST: "db"
      SR_HOMEPAGE_DB_PORT: "3306"
      SR_HOMEPAGE_DB_NAME: "sr_web"
      SR_HOMEPAGE_DB_USER: "root"
      SR_HOMEPAGE_DB_PASSWORD: "password"
  
  db:
    image: mysql:9.7.0
    environment:
      MYSQL_ROOT_PASSWORD: "password"
```

When you run `docker-compose up --build`:
1. Database starts
2. Image builds with static sitemap
3. Container starts and waits for database
4. Sitemap regenerates with blog articles
5. Apache starts serving with complete sitemap

## Troubleshooting

### Sitemap not including blog articles

1. Check database connection:
   ```bash
   docker logs container_name | grep "Sitemap"
   ```

2. Verify database is running:
   ```bash
   docker-compose ps
   ```

3. Check blog table structure:
   ```bash
   docker exec -it db_container mysql -uroot -ppassword sr_web
   mysql> DESCRIBE blog;
   ```

### Sitemap file not created

1. Check file permissions:
   ```bash
   docker exec container_name ls -la /var/www/html/sitemap.xml
   ```

2. Verify Apache process can write to directory:
   ```bash
   docker exec container_name touch /var/www/html/test.txt
   ```

### Docker build fails

1. Check PHP syntax:
   ```bash
   php -l generate-sitemap.php
   ```

2. Verify file paths in Dockerfile are correct

## Search Engine Integration

After deploying, submit the sitemap to search engines:

### Google Search Console
1. Go to https://search.google.com/search-console
2. Add property for your domain
3. Submit sitemap: `https://swimresults.de/sitemap.xml`

### Bing Webmaster Tools
1. Go to https://www.bing.com/webmasters
2. Add and verify your site
3. Submit sitemap URL

## Database Requirements

The blog table should have these columns:

```sql
CREATE TABLE blog (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  content_html LONGTEXT,
  content_md LONGTEXT,
  author VARCHAR(255),
  img_src VARCHAR(255),
  created_at DATETIME NOT NULL,
  published_at DATETIME,
  updated_at DATETIME
);
```

The generator looks for:
- `id` — Unique article identifier
- `title` — Article title (converted to URL slug)
- `published_at` — Publication date (only includes if <= NOW())
- `updated_at` — Last modification date (uses for `lastmod` in sitemap)

