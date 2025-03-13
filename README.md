# TEMPLATE - Proyecto Laravel 11 con Maru UI

Este es un proyecto (template) basado en **Laravel 11** utilizando **Maru UI** para la interfaz de usuario. Sigue los pasos a continuación para configurarlo correctamente en tu entorno de desarrollo.

---

## 🚀 Instalación y configuración

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/OsmarLG/liartechnologies-system-v1-template.git
cd liartechnologies-system-v1-template
```

### 2️⃣ Instalar dependencias de PHP
```bash
composer update
```

### 3️⃣ Configurar el entorno
Copia el archivo de ejemplo `.env.example` y renómbralo como `.env`:
```bash
cp .env.example .env
```
Luego, abre el archivo `.env` y configura la base de datos y otras variables necesarias.

### 4️⃣ Generar clave de aplicación
```bash
php artisan key:generate
```

### 5️⃣ Migrar la base de datos
Ejecuta las migraciones para crear las tablas necesarias en la base de datos:
```bash
php artisan migrate
```
Opcional: Si quieres migrar y rellenar la base de datos con datos de prueba:
```bash
php artisan migrate --seed
```

### 6️⃣ Crear enlace simbólico para el almacenamiento
Laravel necesita un enlace simbólico para acceder a los archivos en `storage/app/public`. Ejecuta:
```bash
php artisan storage:link
```

### 7️⃣ Instalar dependencias de Node.js
```bash
npm install
```

### 8️⃣ Compilar los assets del frontend
```bash
npm run dev
```
Si quieres generar los assets para producción, usa:
```bash
npm run build
```

---

## 🎯 **Ejecutar el servidor de desarrollo**
Para iniciar el servidor de Laravel, usa el siguiente comando:
```bash
php artisan serve
```
Esto iniciará la aplicación en `http://127.0.0.1:8000`.

---

## 📦 **Tecnologías utilizadas**
- **Laravel 11** - Framework de PHP
- **Maru UI** - Componentes UI para Laravel
- **Tailwind CSS** - Estilización moderna
- **Vite** - Compilador de assets
- **Alpine.js** - Interactividad ligera en el frontend

---

## 🤝 **Contribuciones**
Si deseas contribuir, por favor abre un **issue** o envía un **pull request**. ¡Toda ayuda es bienvenida! 🎉

---

## 👤 **Creador**
Este proyecto fue desarrollado por **Osmar Alejandro Liera Gomez**.

- 🌎 [Sitio Web](https://liartechnologies.com/)
- 🐙 [GitHub](https://github.com/OsmarLG)
- 🔗 [LinkedIn](https://www.linkedin.com/in/osmar-alejandro-liera-gomez-ab1b93204/)

---

## 📄 **Licencia**
Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).