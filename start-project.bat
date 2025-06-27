@echo off 
title Gestock - Inicio de proyecto 
echo ================================ 
echo Iniciando el backend (Laravel)... 
echo ================================ 
start cmd /k "cd backend && php artisan serve" 
timeout /t 2 > nul 
echo ================================ 
echo Iniciando el frontend (Angular)... 
echo ================================ 
start cmd /k "cd frontend && npm start" 
echo Proyecto Gestock iniciado correctamente. 
