@echo off
echo ========================================
echo   Sistema de Gerenciamento de Eventos
echo             SQLite Version
echo ========================================
echo.

echo Verificando se o PHP está instalado...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERRO: PHP não está instalado ou não está no PATH
    echo Por favor, instale o PHP primeiro
    pause
    exit /b 1
)

echo PHP encontrado!
echo.

echo Testando conexão com SQLite...
php test-sqlite.php
if %errorlevel% neq 0 (
    echo.
    echo AVISO: Houve problemas na inicialização do banco
    echo O sistema pode não funcionar corretamente
    echo.
)

echo.
echo Iniciando servidor PHP built-in...
echo.
echo Acesse o sistema em: http://localhost:8000
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
echo IMPORTANTE: O banco SQLite será criado automaticamente
echo na pasta 'database/' quando você acessar o sistema
echo.

php -S localhost:8000
