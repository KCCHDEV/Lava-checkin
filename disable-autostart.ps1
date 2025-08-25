# Laravel Auto-Start Disable Script
Write-Host "Disabling Laravel Auto-Start..." -ForegroundColor Yellow

# Get startup folder
$startupFolder = [Environment]::GetFolderPath("Startup")
$shortcutPath = Join-Path $startupFolder "Laravel Auto-Start.lnk"

# Remove shortcut if exists
if (Test-Path $shortcutPath) {
    Remove-Item $shortcutPath -Force
    Write-Host "Laravel Auto-Start has been disabled successfully!" -ForegroundColor Green
    Write-Host "Removed shortcut: $shortcutPath" -ForegroundColor Yellow
} else {
    Write-Host "Laravel Auto-Start was not found in startup folder." -ForegroundColor Red
}

# Also remove from registry if exists
try {
    Remove-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\Run" -Name "LaravelDevelopmentServer" -ErrorAction SilentlyContinue
    Write-Host "Registry entry removed (if existed)." -ForegroundColor Yellow
} catch {
    # Ignore errors if entry doesn't exist
}

Write-Host "`nPress any key to exit..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
