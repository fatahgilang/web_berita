#!/bin/bash

echo "Starting build process..."

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Run npm build
echo "Building frontend assets..."
npm run build

# Generate application key if not exists
echo "Generating application key..."
php artisan key:generate --force

echo "Build process completed!"