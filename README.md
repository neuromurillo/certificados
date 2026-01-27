# 📘 Sistema de Generación y Validación de Certificados
**Academia Mexicana de Neurología**

Este sistema permite gestionar cursos, registrar asistentes, generar certificados en PDF, enviarlos por correo electrónico y validar su autenticidad mediante códigos QR. Está diseñado para uso institucional, con un enfoque en claridad, mantenibilidad y confiabilidad.

## 🧩 Características principales
- Generación automática de certificados en PDF  
- Envío masivo de certificados por correo (SMTP)  
- Validación mediante código QR  
- Gestión de cursos y asistentes  
- Generación de ZIP con certificados  
- Configuración centralizada mediante `.env`  
- Flujo Git profesional para desarrollo y mantenimiento  

## 🛠️ Requisitos del sistema
- PHP 8.x  
- MySQL 5.7+  
- Composer  
- Apache o Nginx  
- Extensiones PHP necesarias: gd, mbstring, openssl, pdo_mysql, zip  

## 📦 Instalación
```bash
git clone https://github.com/neuromurillo/certificados.git
cd certificados
composer install

Este proyecto es creado por Gurpo Científico IPAO, y su uso requiere autorización.
