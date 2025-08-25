# Laravel Auto-Start Setup Script
# Run this script as Administrator

Write-Host "Setting up Laravel Auto-Start..." -ForegroundColor Green

# Get the current directory
$currentDir = Get-Location
$batchFile = Join-Path $currentDir "start-laravel-background.bat"

# Create the startup registry entry
$startupPath = "HKCU:\Software\Microsoft\Windows\CurrentVersion\Run"
$startupName = "LaravelDevelopmentServer"
$startupValue = "`"$batchFile`""

try {
    # Check if running as administrator
    if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
        Write-Host "Please run this script as Administrator!" -ForegroundColor Red
        Write-Host "Right-click on PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
        pause
        exit
    }

    # Add to startup
    Set-ItemProperty -Path $startupPath -Name $startupName -Value $startupValue -Type String
    
    Write-Host "Laravel Auto-Start has been configured successfully!" -ForegroundColor Green
    Write-Host "Laravel will start automatically when you log in to Windows." -ForegroundColor Yellow
    Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Cyan
    
    # Test the batch file
    Write-Host "`nTesting Laravel startup..." -ForegroundColor Yellow
    & $batchFile
    
} catch {
    Write-Host "Error setting up auto-start: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nPress any key to exit..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
