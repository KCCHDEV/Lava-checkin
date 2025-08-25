#!/bin/bash

echo "========================================"
echo "   Laravel Project Backup Script"
echo "   Attendance Management System - Lamphun Technical College"
echo "========================================"
echo

# Create backup directory with timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="backup_$TIMESTAMP"

echo "Creating backup directory: $BACKUP_DIR"
mkdir -p "$BACKUP_DIR"

# Copy essential project files and folders
echo
echo "[1/6] Copying project files..."
cp -r app "$BACKUP_DIR/"
cp -r bootstrap "$BACKUP_DIR/"
cp -r config "$BACKUP_DIR/"
cp -r database "$BACKUP_DIR/"
cp -r public "$BACKUP_DIR/"
cp -r resources "$BACKUP_DIR/"
cp -r routes "$BACKUP_DIR/"
cp -r storage "$BACKUP_DIR/"
cp -r tests "$BACKUP_DIR/"
cp -r vendor "$BACKUP_DIR/"
cp artisan "$BACKUP_DIR/"
cp composer.json "$BACKUP_DIR/"
cp composer.lock "$BACKUP_DIR/"
cp package.json "$BACKUP_DIR/"
cp vite.config.js "$BACKUP_DIR/"
echo "✓ Project files copied successfully"

# Backup SQLite database
echo
echo "[2/6] Backing up database..."
if [ -f database/database.sqlite ]; then
    cp database/database.sqlite "$BACKUP_DIR/database/"
    echo "✓ Database backed up successfully"
else
    echo "! No database file found"
fi

# Create database dump
echo
echo "[3/6] Creating database dump..."
if [ -f database/database.sqlite ]; then
    sqlite3 database/database.sqlite .dump > "$BACKUP_DIR/database_dump.sql"
    echo "✓ Database dump created successfully"
else
    echo "! No database file found for dump"
fi

# Create system information file
echo
echo "[4/6] Creating system information..."
{
    echo "System Information"
    echo "=================="
    echo
    echo "Backup Date: $(date)"
    echo
    echo "PHP Version:"
    php --version
    echo
    echo "Composer Version:"
    composer --version
    echo
    echo "Laravel Version:"
    php artisan --version
} > "$BACKUP_DIR/system_info.txt"
echo "✓ System information created successfully"

# Create restore instructions
echo
echo "[5/6] Creating restore instructions..."
{
    echo "Restore Instructions"
    echo "==================="
    echo
    echo "To restore this backup:"
    echo "1. Extract this backup to a new directory"
    echo "2. Open Terminal in the restored directory"
    echo "3. Run: ./install.sh"
    echo "4. If you want to restore the database:"
    echo "   - Copy database_dump.sql to the new project"
    echo "   - Run: sqlite3 database/database.sqlite < database_dump.sql"
    echo "5. Start the server: ./start-server.sh"
} > "$BACKUP_DIR/RESTORE_INSTRUCTIONS.txt"
echo "✓ Restore instructions created successfully"

# Compress backup
echo
echo "[6/6] Compressing backup..."
tar -czf "$BACKUP_DIR.tar.gz" "$BACKUP_DIR"
if [ $? -eq 0 ]; then
    echo "✓ Backup compressed successfully"
    echo
    echo "========================================"
    echo "   Backup Completed Successfully!"
    echo "========================================"
    echo
    echo "Backup file: $BACKUP_DIR.tar.gz"
    echo "Backup size: $(du -h "$BACKUP_DIR.tar.gz" | cut -f1)"
    echo
    echo "Backup location: $(pwd)/$BACKUP_DIR.tar.gz"
    echo
    echo "========================================"
    
    # Clean up temporary directory
    rm -rf "$BACKUP_DIR"
    echo "Temporary files cleaned up"
else
    echo "ERROR: Failed to compress backup"
    echo "Backup files are in: $BACKUP_DIR"
fi

echo
