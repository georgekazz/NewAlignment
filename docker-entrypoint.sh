#!/bin/bash

# Αναμονή για να ξεκινήσει η βάση δεδομένων
echo "Waiting for database..."
sleep 10

# Εκτέλεση των Laravel migrations & seeding
php artisan migrate --force

SEED_COUNT=$(php artisan tinker --execute='echo \App\Models\AdminUser::count();' | tr -d '[:space:]')

if [ "$SEED_COUNT" -eq "0" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
else
    echo "Database already seeded. Skipping..."
fi

# Εκκίνηση Apache
exec apache2-foreground
