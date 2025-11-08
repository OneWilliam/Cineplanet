# Estructura SQL

Esta carpeta contiene los archivos SQL organizados por tipo.

## 📁 Estructura

```
sql/
├── migrations/        # Cambios en la estructura de la base de datos
│   ├── 001_estructura_tablas.sql    # Creación de todas las tablas
│   └── 002_procedures.sql            # Stored procedures
└── seeds/            # Datos iniciales para la base de datos
    └── 001_datos_iniciales.sql      # Datos de prueba
```

## 🚀 Uso

### Primera vez (crear base de datos desde cero)
```bash
# Hacer rollback completo
php vendor/bin/phinx rollback -t 0

# Ejecutar todas las migraciones
php vendor/bin/phinx migrate

# Insertar datos iniciales
php vendor/bin/phinx seed:run
```

### Ver estado de migraciones
```bash
php vendor/bin/phinx status
```

### Rollback (deshacer última migración)
```bash
php vendor/bin/phinx rollback
```

### Rollback completo
```bash
php vendor/bin/phinx rollback -t 0
```

## 📝 Notas

- Las migraciones de Phinx (en `db/migrations/`) leen estos archivos SQL
- Los seeds de Phinx (en `db/seeds/`) leen los archivos de la carpeta `seeds/`
- La configuración de la base de datos se toma del archivo `.env`
- Los nombres de archivos están numerados para mantener el orden de ejecución
