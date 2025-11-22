# Arquitectura SPA con Componentes - Documentación

## Visión General

Este proyecto implementa una **Single Page Application (SPA)** con arquitectura de componentes, donde el backend PHP actúa exclusivamente como API que devuelve JSON, y el frontend maneja completamente el renderizado de la interfaz.

## Stack Tecnológico

### Backend
- **Framework:** Slim PHP
- **Lenguaje:** PHP 8+
- **Tipo:** API RESTful
- **Salida:** JSON únicamente

### Frontend
- **Lenguaje:** JavaScript (ES6+)
- **Framework Ligero:** Alpine.js (para estado local)
- **Arquitectura:** Componentes con Router Cliente
- **Estilos:** CSS modular por componente
- **Plantillas:** HTML dinámico generado por JS

## Estructura de Directorios

```
proyecto/
├── public/
│   ├── index.html              # Página principal SPA
│   ├── js/
│   │   ├── Router.js          # Sistema de enrutamiento cliente
│   │   └── components/        # Componentes JS globales
│   ├── pages/
│   │   ├── home/
│   │   │   ├── Home.js        # Componente Home
│   │   │   ├── Home.html      # Plantilla HTML
│   │   │   └── Home.css       # Estilos locales
│   │   ├── movies/
│   │   │   ├── Movies.js
│   │   │   ├── Movies.html
│   │   │   └── Movies.css
│   ├── components/
│   │   ├── movie-card/
│   │   │   ├── MovieCard.js
│   │   │   ├── MovieCard.html
│   │   │   └── MovieCard.css
│   │   └── header/
│   ├── css/
│   ├── img/
│   └── assets/
├── src/
│   └── Controllers/
└── .htaccess
```

## Arquitectura Backend

### Rutas API
- Todas las rutas que empiezan con `/api/` son manejadas por Slim
- Devuelven únicamente JSON
- No renderizan vistas HTML

### Ejemplo de Controlador
```php
public function homeFragment(Request $request, Response $response, $args): Response
{
    $peliculas = $this->movieRepository->getAllMovies();
    $peliculas = $this->imageService->getMovieImages($peliculas);
    
    $response->getBody()->write(json_encode($peliculas));
    return $response->withHeader('Content-Type', 'application/json');
}
```

## Arquitectura Frontend

### Sistema de Componentes

Cada componente tiene 3 archivos:
- `Componente.js` - Lógica del componente
- `Componente.html` - Plantilla HTML
- `Componente.css` - Estilos locales

#### Ejemplo de Componente
```javascript
class MovieCardComponent {
    constructor() {
        this.template = null;
        this.cssLoaded = false;
    }

    async loadTemplate() {
        if (!this.template) {
            const response = await fetch('/components/movie-card/MovieCard.html');
            this.template = await response.text();
        }
        return this.template;
    }

    async render(data) {
        await this.loadCSS();
        const template = await this.loadTemplate();
        
        return template
            .replace('{{imagen}}', data.imagen)
            .replace('{{titulo}}', data.titulo);
    }
}
```

### Router Cliente

Maneja la navegación entre páginas sin recarga:
```javascript
class Router {
    constructor() {
        this.routes = {
            '/': 'HomePage',
            '/peliculas': 'MoviesPage'
        };
        window.addEventListener('popstate', this.handleRouteChange.bind(this));
    }
    
    async navigate(path) {
        history.pushState({}, '', path);
        await this.handleRouteChange();
    }
}
```

### Gestión de Estados
- **Alpine.js** para estado local en componentes
- **Alpine Store** para estado global compartido

## Configuración de .htaccess

```apache
RewriteEngine On

# Rutas API al backend Slim
RewriteCond %{REQUEST_URI} ^/api/
RewriteRule ^(.*)$ public/index.php [QSA,L]

# Recursos estáticos desde public/
RewriteCond %{REQUEST_URI} \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|json|xml|map)$
RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -f
RewriteRule ^(.*)$ public/$1 [QSA,L]

# Otras rutas al frontend SPA
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/index.html [QSA,L]
```

## Flujo de Navegación

1. Usuario accede a `http://dominio.com/`
2. Servidor sirve `public/index.html`
3. Frontend detecta ruta `/` y carga `HomePage`
4. `HomePage` hace fetch a `/api/home` para obtener datos
5. Componente renderiza HTML dinámico
6. Al cambiar de página, Router actualiza URL y carga nuevo componente

## Patrones de Desarrollo

### Componentes Reutilizables
- Cada componente es autocontenido
- Carga su propia plantilla y estilos
- Recibe datos y devuelve HTML generado

### Separación de Responsabilidades
- **Backend PHP**: Únicamente lógica de negocio, base de datos → JSON
- **Frontend JS**: UI, UX, interacciones, manejo de estados → HTML

### Organización de CSS
- Estilos locales: Por componente
- Estilos globales: En `css/base.css`
- Componentes no dependen de estilos externos

## Beneficios de la Arquitectura

1. **Clara separación de backend/frontend**
2. **Frontend con control total de la UI**
3. **Backend reutilizable para múltiples clientes**
4. **Componentes reutilizables y modulares**
5. **Experiencia de usuario similar a una aplicación nativa**
6. **Escalabilidad horizontal del backend**
7. **Flexibilidad total en la implementación del UI**

## Consideraciones Importantes

### Desventajas
- Mayor complejidad JS que con HTML renderizado en servidor
- Mayor tamaño inicial de bundle JS
- SEO requiere SSR o pre-renderizado si es necesario

### Buenas Prácticas Implementadas
- Componentes encapsulados
- Carga dinámica de recursos
- Separación estricta de responsabilidades
- Sistema de rutas cliente
- Gestión de estado modular

---

*Esta arquitectura es ideal para aplicaciones web modernas donde se requiere una experiencia de usuario rica y control total sobre la interfaz, manteniendo una clara separación entre lógica de negocio y presentación.*