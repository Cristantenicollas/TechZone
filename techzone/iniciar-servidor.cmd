@echo off
REM Sobe o TechZone no servidor embutido do PHP.
REM Uso: click duas vezes neste arquivo. Para parar: Ctrl+C na janela preta.

setlocal
REM A raiz do site e a pasta acima de techzone\, porque BASE_URL = "/techzone".
set RAIZ=%~dp0..
set PORTA=8080
set URL=http://localhost:%PORTA%/techzone/

echo.
echo   ============================================
echo    TechZone
echo   ============================================
echo.
echo   Endereco: %URL%
echo   Servindo: %RAIZ%
echo.
echo   Deixa esta janela ABERTA enquanto usar o site.
echo   Para parar, aperte Ctrl+C aqui.
echo.
echo   ATENCAO: o catalogo inteiro vem do PostgreSQL remoto
echo   (192.168.10.37:5432, configurado em includes\config.php). Sem
echo   acesso a esse servidor, as paginas mostram "Banco de dados
echo   indisponivel".
echo.
echo   Abrindo o navegador em 2 segundos...
echo.

cd /d "%RAIZ%"
start "" "%URL%"
php -S localhost:%PORTA% -t "%RAIZ%"

endlocal