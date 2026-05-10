#!/bin/bash
# Docker entrypoint script
# Runs sitemap generation and then starts Apache

set -e

echo "Starting SwimResults homepage container..."

# Wait for MySQL database if configured
if [ "$SR_HOMEPAGE_DB_HOST" != "" ] && [ "$SR_HOMEPAGE_DB_DISABLE" != "true" ]; then
    echo "Waiting for database at $SR_HOMEPAGE_DB_HOST:${SR_HOMEPAGE_DB_PORT:-3306}..."
    
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if nc -z "$SR_HOMEPAGE_DB_HOST" "${SR_HOMEPAGE_DB_PORT:-3306}" 2>/dev/null; then
            echo "✓ Database is available"
            break
        fi
        
        attempt=$((attempt + 1))
        echo "  Attempt $attempt/$max_attempts - waiting..."
        sleep 1
    done
    
    if [ $attempt -eq $max_attempts ]; then
        echo "⚠ Database did not become available within timeout"
        echo "  Will generate sitemap with static pages only"
    fi
fi

# Generate sitemap with database content if available
echo "Generating sitemap..."
if php /generate-sitemap.php; then
    echo "✓ Sitemap generation completed"
else
    echo "⚠ Sitemap generation had issues, but container will continue"
fi

echo "Starting Apache..."

# Execute Apache in foreground
exec apache2-foreground
