# Laravel Auto-Start Setup (Simple Version)
Write-Host "Setting up Laravel Auto-Start..." -ForegroundColor Green

# Get current directory and batch file path
$currentDir = Get-Location
$batchFile = Join-Path $currentDir "start-laravel-background.bat"

# Create startup folder shortcut
$startupFolder = [Environment]::GetFolderPath("Startup")
$shortcutPath = Join-Path $startupFolder "Laravel Auto-Start.lnk"

# Create shortcut
$WshShell = New-Object -comObject WScript.Shell
$Shortcut = $WshShell.CreateShortcut($shortcutPath)
$Shortcut.TargetPath = $batchFile
$Shortcut.WorkingDirectory = $currentDir
$Shortcut.Description = "Laravel Development Server Auto-Start"
$Shortcut.Save()

Write-Host "Laravel Auto-Start has been configured successfully!" -ForegroundColor Green
Write-Host "Shortcut created at: $shortcutPath" -ForegroundColor Yellow
Write-Host "Laravel will start automatically when you log in to Windows." -ForegroundColor Cyan
Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Cyan

Write-Host "`nPress any key to exit..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
